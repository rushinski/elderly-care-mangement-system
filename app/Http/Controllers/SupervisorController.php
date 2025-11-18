<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roster;
use App\Models\User;
use App\Models\Report;

class SupervisorController extends Controller
{
    /**
     * Display supervisor dashboard with staff rosters and pending reports.
     */
    public function index()
    {
        $rosters = Roster::with('user')->latest()->get();
        $reports = Report::where('status', 'pending')->latest()->get();

        return view('supervisor.dashboard', compact('rosters', 'reports'));
    }

    /**
     * Display form for creating a new roster entry.
     */
    public function create()
    {
        $staff = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Caregiver', 'Doctor', 'Nurse']);
        })->get();

        return view('supervisor.create-roster', compact('staff'));
    }

    /**
     * Store a new roster assignment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'shift' => 'required|string|max:50',
        ]);

        Roster::create($validated);

        return redirect()->route('supervisor.index')->with('success', 'Roster created successfully.');
    }

    /**
     * Edit an existing roster entry.
     */
    public function edit(Roster $roster)
    {
        $staff = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Caregiver', 'Doctor', 'Nurse']);
        })->get();

        return view('supervisor.edit-roster', compact('roster', 'staff'));
    }

    /**
     * Update an existing roster entry.
     */
    public function update(Request $request, Roster $roster)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'shift' => 'required|string|max:50',
        ]);

        $roster->update($validated);

        return redirect()->route('supervisor.index')->with('success', 'Roster updated successfully.');
    }

    /**
     * Delete a roster entry.
     */
    public function destroy(Roster $roster)
    {
        $roster->delete();

        return redirect()->route('supervisor.index')->with('success', 'Roster entry deleted successfully.');
    }

    /**
     * Approve or reject pending reports.
     */
    public function reviewReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $report->update($validated);

        return redirect()->route('supervisor.index')->with('success', 'Report status updated.');
    }
}
