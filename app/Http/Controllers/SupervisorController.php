<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roster;
use App\Models\User;
use App\Models\Report;
use App\Models\Appointment;
use App\Models\Patient;

class SupervisorController extends Controller
{
    /**
     * Display Supervisor Dashboard — overview of rosters, reports, etc.
     */
    public function index()
    {
        $rosters = Roster::with(['supervisor', 'doctor', 'caregiver1', 'caregiver2', 'caregiver3', 'caregiver4'])
            ->latest()
            ->get();

        $reports = Report::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $employeeCount = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Caregiver', 'Doctor', 'Nurse']);
        })->count();

        $patientCount = Patient::count();
        $pendingReports = $reports->count();
        $appointmentsCount = Appointment::whereDate('appointment_date', '>=', now())->count();

        return view('supervisor.dashboard', compact(
            'rosters',
            'reports',
            'employeeCount',
            'patientCount',
            'pendingReports',
            'appointmentsCount'
        ));
    }

    /**
     * Show all employees (read-only view using Admin's blade).
     */
    public function employees()
    {
        $employees = User::with('role')
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['Caregiver', 'Doctor', 'Nurse']);
            })
            ->paginate(10);

        $readonly = true;

        return view('admin.employees.index', compact('employees', 'readonly'));
    }

    /**
     * Display all reports for the supervisor (with filtering).
     */
    public function reports(Request $request)
    {
        $status = $request->query('status');
        $query = Report::query();

        if ($status) {
            $query->where('status', $status);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(10);
        $readonly = false; // Supervisor can approve/reject

        return view('admin.reports', compact('reports', 'status', 'readonly'));
    }

    /**
     * Display doctor appointments (read-only for Supervisor).
     */
    public function appointments()
    {
        $appointments = Appointment::with(['doctor', 'patient'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        $readonly = true;

        return view('admin.appointments.index', compact('appointments', 'readonly'));
    }

    /**
     * Show form for creating a new roster.
     */
    public function create()
    {
        $supervisors = User::whereHas('role', fn($q) => $q->where('name', 'Supervisor'))->get();
        $doctors = User::whereHas('role', fn($q) => $q->where('name', 'Doctor'))->get();
        $caregivers = User::whereHas('role', fn($q) => $q->where('name', 'Caregiver'))->get();

        return view('supervisor.create-roster', compact('supervisors', 'doctors', 'caregivers'));
    }

    

    /**
     * Show a specific roster entry in full detail.
     */
    public function show(Roster $roster)
    {
        $roster->load(['supervisor', 'doctor', 'caregiver1', 'caregiver2', 'caregiver3', 'caregiver4']);
        return view('supervisor.view-roster', compact('roster'));
    }

    /**
     * Show edit form for an existing roster.
     */
    public function edit(Roster $roster)
    {
        $supervisors = User::whereHas('role', fn($q) => $q->where('name', 'Supervisor'))->get();
        $doctors = User::whereHas('role', fn($q) => $q->where('name', 'Doctor'))->get();
        $caregivers = User::whereHas('role', fn($q) => $q->where('name', 'Caregiver'))->get();

        return view('supervisor.edit-roster', compact('roster', 'supervisors', 'doctors', 'caregivers'));
    }

        // Store a new roster record.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supervisor_id'   => 'required|exists:users,id',
            'doctor_id'       => 'required|exists:users,id',
            'caregiver_1_id'  => 'nullable|exists:users,id',
            'caregiver_2_id'  => 'nullable|exists:users,id',
            'caregiver_3_id'  => 'nullable|exists:users,id',
            'caregiver_4_id'  => 'nullable|exists:users,id',
            'date'            => 'required|date',
        ]);

        // Prevent duplicate caregivers
        $caregivers = [
            $request->input('caregiver_1_id'),
            $request->input('caregiver_2_id'),
            $request->input('caregiver_3_id'),
            $request->input('caregiver_4_id'),
        ];

        $filtered = array_filter($caregivers);
        if (count($filtered) !== count(array_unique($filtered))) {
            return back()
                ->withErrors(['caregivers' => 'Each caregiver must be unique.'])
                ->withInput();
        }

        Roster::create($validated);

        return redirect()
            ->route('supervisor.dashboard')
            ->with('success', 'Roster created successfully.');
    }

    // Update an existing roster record.
    public function update(Request $request, Roster $roster)
    {
        $validated = $request->validate([
            'supervisor_id'   => 'required|exists:users,id',
            'doctor_id'       => 'required|exists:users,id',
            'caregiver_1_id'  => 'nullable|exists:users,id',
            'caregiver_2_id'  => 'nullable|exists:users,id',
            'caregiver_3_id'  => 'nullable|exists:users,id',
            'caregiver_4_id'  => 'nullable|exists:users,id',
            'date'            => 'required|date',
        ]);

        $caregivers = [
            $request->input('caregiver_1_id'),
            $request->input('caregiver_2_id'),
            $request->input('caregiver_3_id'),
            $request->input('caregiver_4_id'),
        ];

        $filtered = array_filter($caregivers);
        if (count($filtered) !== count(array_unique($filtered))) {
            return back()
                ->withErrors(['caregivers' => 'Each caregiver must be unique.'])
                ->withInput();
        }

        $roster->update($validated);

        return redirect()
            ->route('supervisor.dashboard')
            ->with('success', 'Roster updated successfully.');
    }


    /**
     * Delete a roster record.
     */
    public function destroy(Roster $roster)
    {
        $roster->delete();

        return redirect()
            ->route('supervisor.dashboard')
            ->with('success', 'Roster entry deleted successfully.');
    }

    /**
     * Approve or reject pending reports.
     */
    public function reviewReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $report->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('supervisor.reports')
            ->with('success', 'Report status updated successfully.');
    }
}
