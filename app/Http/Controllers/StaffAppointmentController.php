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
     * Optional query params: ?patient_id=&date=
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
            'patient_id'        => 'required|exists:patients,id',
            'appointment_date'  => 'required|date',
            'appointment_time'  => 'required',
            'doctor_id'         => [
                'required',
                'exists:doctors,id',
                function ($attribute, $value, $fail) use ($request) {
                    $doctor = Doctor::find($value);
                    if (!$doctor) {
                        return;
                    }

                    // Doctor must appear on the Roster (doctor_id is USER id in roster)
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
            'doctor_id'        => $validated['doctor_id'],  // Doctor::id
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'status'           => 'scheduled',
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
     * AJAX: return doctors on the roster for a given date.
     */
    public function doctorsByDate(Request $request)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json([]);
        }

        // doctor_id in roster is USER id
        $doctorUserIds = Roster::whereDate('date', $date)
            ->pluck('doctor_id')
            ->unique();

        if ($doctorUserIds->isEmpty()) {
            return response()->json([]);
        }

        $doctors = Doctor::with('user')
            ->whereIn('user_id', $doctorUserIds)
            ->get();

        return response()->json(
            $doctors->map(function (Doctor $doctor) {
                return [
                    'id'   => $doctor->id,
                    'name' => $doctor->user?->full_name ?? 'Doctor #'.$doctor->id,
                ];
            })
        );
    }
}
