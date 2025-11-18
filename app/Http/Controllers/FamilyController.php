<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\FamilyMember;
use App\Models\Appointment;
use App\Models\Prescription;

class FamilyController extends Controller
{
    /**
     * Display family dashboard showing linked patient details.
     */
    public function index()
    {
        $userId = auth()->id();
        $familyMember = FamilyMember::where('user_id', $userId)->firstOrFail();

        $patient = Patient::where('id', $familyMember->patient_id)->first();
        $appointments = Appointment::where('patient_id', $patient->id)->latest()->get();
        $prescriptions = Prescription::where('patient_id', $patient->id)->latest()->get();

        return view('family.dashboard', compact('patient', 'appointments', 'prescriptions'));
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
     * Verify access through family-patient link.
     */
    private function authorizeFamilyAccess($patientId)
    {
        $userId = auth()->id();
        $isLinked = FamilyMember::where('user_id', $userId)
            ->where('patient_id', $patientId)
            ->exists();

        if (!$isLinked) {
            abort(403, 'Unauthorized access.');
        }
    }
}
