<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Patient;
use Carbon\Carbon;

class DoctorController extends Controller
{
    /**
     * Display doctor dashboard with appointments and prescriptions.
     */
    public function index(Request $request)
    {
        $doctorId = auth()->user()->doctor->id;

        // ====================================
        // 1. Collect all patient IDs linked to this doctor
        // ====================================
        $patientIds = Appointment::where('doctor_id', $doctorId)
            ->pluck('patient_id')
            ->unique();

        // ====================================
        // 2. Latest prescription per patient
        // ====================================
        $latestPrescriptions = Prescription::whereIn('patient_id', $patientIds)
            ->with(['patient.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('patient_id')
            ->map(fn($group) => $group->first());

        // ====================================
        // 3. Old appointments (before today)
        // ====================================
        $oldAppointments = Appointment::with('patient.user')
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', '<', today())
            ->orderByDesc('appointment_date')
            ->get();

        // ====================================
        // 4. Upcoming appointments (today → selected till-date)
        // ====================================
        // Default = today; replaced if query string is provided
        $tillDate = $request->query('till_date', today()->format('Y-m-d'));

        $upcomingAppointments = Appointment::with('patient.user')
            ->where('doctor_id', $doctorId)
            ->whereBetween(
                'appointment_date',
                [today()->format('Y-m-d'), $tillDate]
            )
            ->orderBy('appointment_date')
            ->get();

        return view('doctor.dashboard', [
            'latestPrescriptions' => $latestPrescriptions,
            'oldAppointments'     => $oldAppointments,
            'upcomingAppointments'=> $upcomingAppointments,
            'tillDate'            => $tillDate,
        ]);
    }

    /**
     * Show form for creating a new appointment.
     */
    public function create()
    {
        $patients = Patient::all();
        return view('doctor.create-appointment', compact('patients'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'time' => 'required',
            'notes' => 'nullable|string',
        ]);

        Appointment::create([
            'patient_id' => $validated['patient_id'],
            'appointment_date' => $validated['date'],
            'appointment_time' => $validated['time'],
            'notes' => $validated['notes'] ?? null,
            'doctor_id' => auth()->user()->doctor->id ?? null,
        ]);

        return redirect()
            ->route('doctor.dashboard')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Show a specific appointment.
     */
    public function show(Appointment $appointment)
    {
        return view('doctor.view-appointment', compact('appointment'));
    }

    /**
     * Edit an appointment.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::all();
        return view('doctor.edit-appointment', compact('appointment', 'patients'));
    }

    /**
     * Update an existing appointment.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'time' => 'required',
            'notes' => 'nullable|string',
        ]);

        $appointment->update([
            'patient_id' => $validated['patient_id'],
            'appointment_date' => $validated['date'],
            'appointment_time' => $validated['time'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('doctor.dashboard')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Delete an appointment.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()
            ->route('doctor.dashboard')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Show a patient's prescriptions and form for new ones.
     */
    public function showPatient(Patient $patient)
    {
        $doctorId = auth()->user()->doctor->id ?? null;
        $today = Carbon::today();

        // ✅ Get the most recent appointment for today or earlier
        $latestAppointment = Appointment::where('doctor_id', $doctorId)
            ->where('patient_id', $patient->id)
            ->whereDate('appointment_date', '<=', $today)
            ->orderByDesc('appointment_date')
            ->first();

        // ✅ Get all prescriptions for this patient
        $prescriptions = Prescription::where('patient_id', $patient->id)
            ->orderByDesc('created_at')
            ->get();

        // ✅ Allow new prescription only if appointment is today & not completed
        $canPrescribe = $latestAppointment
            && Carbon::parse($latestAppointment->appointment_date)->isSameDay($today)
            && $latestAppointment->status !== 'Completed';

        return view('doctor.patient-prescription', compact(
            'patient',
            'latestAppointment',
            'prescriptions',
            'canPrescribe'
        ));
    }



    /**
     * Store a new prescription (only allowed on appointment day).
     */
    public function storePrescription(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id' => 'required|exists:patients,id',
            'morning_med' => 'nullable|boolean',
            'afternoon_med' => 'nullable|boolean',
            'night_med' => 'nullable|boolean',
            'comment' => 'nullable|string',
        ]);

        $appointment = Appointment::find($validated['appointment_id']);

        // Check date (you can comment this during testing)
        if (!$appointment || !\Carbon\Carbon::parse($appointment->appointment_date)->isSameDay(\Carbon\Carbon::today())) {
            return back()->with('error', 'You can only add prescriptions on the appointment date.');
        }

        // Convert checkboxes to readable text
        $validated['morning_med'] = $request->has('morning_med') ? 'Prescribed' : null;
        $validated['afternoon_med'] = $request->has('afternoon_med') ? 'Prescribed' : null;
        $validated['night_med'] = $request->has('night_med') ? 'Prescribed' : null;

        Prescription::create($validated);

        $appointment->update(['status' => 'Completed']);

        return redirect()
            ->route('doctor.patient.show', $validated['patient_id'])
            ->with('success', 'Prescription added successfully.');
    }

}
