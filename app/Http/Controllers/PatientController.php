<?php
// app/Http/Controllers/PatientController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\DailyTask;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Find the Patient record linked to this logged-in user
        $patient = Patient::with('user')
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Date filter: default to today, allow ?date=YYYY-MM-DD
        $selectedDate = $request->query('date');
        if (!$selectedDate) {
            $selectedDate = now()->toDateString();
        }

        // Appointment for that date (adjust column name to your schema)
        $appointment = Appointment::with('doctor.user')
            ->where('patient_id', $patient->id)
            ->whereDate('appointment_date', $selectedDate) // <- change if your column is different
            ->first();

        // Daily tasks for that patient + date, keyed by task_type
        $tasks = DailyTask::where('patient_id', $patient->id)
            ->whereDate('task_date', $selectedDate)
            ->get()
            ->keyBy('task_type');

        // If you already know how caregiver assignment works, you can fetch it here.
        // Placeholder for now:
        $caregiverName = 'N/A'; // TODO: wire from Roster/Caregiver relationship

        return view('patient.home', [
            'patient'       => $patient,
            'selectedDate'  => $selectedDate,
            'appointment'   => $appointment,
            'tasks'         => $tasks,
            'caregiverName' => $caregiverName,
        ]);
    }

    // ... keep your showAppointment, showPrescription, showPayment as-is
}
