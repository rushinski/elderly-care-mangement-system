<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Roster;

class StaffAppointmentController extends Controller
{
    /**
     * Show the "Doctor's Appointment" creation page.
     */
    public function create(Request $request)
    {
        $prefilledPatient = null;
        if ($request->filled('patient_id')) {
            $prefilledPatient = Patient::with('user')->find($request->patient_id);
        }

        $prefilledDate = $request->query('date', now()->toDateString());

        return view('staff.appointments.create', [
            'prefilledPatient' => $prefilledPatient,
            'prefilledDate'    => $prefilledDate,
        ]);
    }

    /**
     * Store the appointment (Admin + Supervisor).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'doctor_id'        => [
                'required',
                'exists:doctors,id',
                function ($attribute, $value, $fail) use ($request) {
                    $doctor = Doctor::find($value);
                    if (!$doctor) {
                        return;
                    }

                    // The roster keeps doctor_id as USERS.id
                    $onRoster = Roster::whereDate('date', $request->appointment_date)
                        ->where('doctor_id', $doctor->user_id)
                        ->exists();

                    if (!$onRoster) {
                        $fail('The selected doctor is not on the roster for the chosen date.');
                    }
                },
            ],
            'notes' => 'nullable|string|max:2000',
        ]);

        Appointment::create([
            'patient_id'       => $validated['patient_id'],
            'doctor_id'        => $validated['doctor_id'],   // doctors.id
            'appointment_date' => $validated['appointment_date'],
            'status'           => 'Scheduled',
            'notes'            => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('staff.appointments.create')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * AJAX: lookup patient by ID and return name.
     */
    public function findPatient(Patient $patient)
    {
        $patient->load('user');

        return response()->json([
            'id'   => $patient->id,
            'name' => $patient->user?->full_name ?? 'N/A',
        ]);
    }

    /**
     * AJAX: get doctors on roster for a given date.
     */
    public function doctorsByDate(Request $request)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json([]);
        }

        // Roster doctor_id => users.id
        $doctorUserIds = Roster::whereDate('date', $date)
            ->pluck('doctor_id')
            ->unique();

        if ($doctorUserIds->isEmpty()) {
            return response()->json([]);
        }

        // Doctors table entries whose user_id is in that list
        $doctors = Doctor::with('user')
            ->whereIn('user_id', $doctorUserIds)
            ->get();

        return response()->json(
            $doctors->map(fn (Doctor $doctor) => [
                'id'   => $doctor->id,                                   // doctors.id
                'name' => $doctor->user?->full_name ?? "Doctor #{$doctor->id}",
            ])
        );
    }
}
