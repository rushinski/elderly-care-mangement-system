<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\FamilyMember;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\DailyTask; 

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        // Only family_code + patient_id required
        if (!$request->family_code || !$request->patient_id) {
            return view('family.dashboard');
        }

        // Validate minimal input
        $request->validate([
            'family_code' => 'required|string',
            'patient_id'  => 'required|integer',
        ]);

        // Confirm the family member is linked to that patient
        $familyMember = FamilyMember::where('family_code', $request->family_code)
            ->where('patient_id', $request->patient_id)
            ->first();

        if (!$familyMember) {
            return back()->with('error', 'Family code + Patient ID do not match any record.');
        }

        $patientId = $request->patient_id;
        $date = $request->date ?? now()->toDateString();

        // Fetch today's appointment
        $appointment = Appointment::with(['doctor.user'])
            ->where('patient_id', $patientId)
            ->whereDate('appointment_date', $date)
            ->first();

        // Prescription created today
        $prescription = Prescription::where('patient_id', $patientId)
            ->whereDate('created_at', $date)
            ->latest()
            ->first();

        // Daily tasks (caregiver, meals, meds)
        $tasks = DailyTask::with(['roster.caregiver.user'])
            ->where('patient_id', $patientId)
            ->whereDate('task_date', $date)
            ->get();

        $caregiver = optional($tasks->first()?->roster?->caregiver?->user)->first_name
                    . ' ' .
                    optional($tasks->first()?->roster?->caregiver?->user)->last_name;

        // Meal helper
        $meal = fn($name) => $tasks->firstWhere('task_name', $name)?->completed ? 'Yes' : 'No';

        $results = [
            'date'              => $date,
            'doctor_name'       => optional($appointment?->doctor?->user)->first_name
                                . ' '
                                . optional($appointment?->doctor?->user)->last_name,
            'appointment_time'  => $appointment?->appointment_time ?? '-',
            'caregiver_name'    => trim($caregiver) ?: '-',
            'morning_med'       => $prescription?->morning_med ? 'Yes' : 'No',
            'afternoon_med'     => $prescription?->afternoon_med ? 'Yes' : 'No',
            'night_med'         => $prescription?->night_med ? 'Yes' : 'No',
            'breakfast'         => $meal('breakfast'),
            'lunch'             => $meal('lunch'),
            'dinner'            => $meal('dinner'),
        ];

        return view('family.dashboard', compact('results'));
    }


    

    /**
     * Show a specific appointment detail.
     */
    public function showAppointment(Appointment $appointment)
    {
        $this->authorizeFamilyAccess($appointment->patient_id);
        return view('family.view-appointment', compact('appointment'));
    }

    /**
     * Show prescription details.
     */
    public function showPrescription(Prescription $prescription)
    {
        $this->authorizeFamilyAccess($prescription->patient_id);
        return view('family.view-prescription', compact('prescription'));
    }

    /**
     * Verify access through family-patient email link.
     */
    private function authorizeFamilyAccess($patientId)
    {
        $user = auth()->user();

        $isLinked = FamilyMember::where('email', $user->email)
            ->where('patient_id', $patientId)
            ->exists();

        if (!$isLinked) {
            abort(403, 'Unauthorized access.');
        }
    }
}
