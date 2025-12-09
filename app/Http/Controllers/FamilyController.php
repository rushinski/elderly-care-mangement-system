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
        $user = auth()->user();

        // Match logged-in family user to their family_members record via email
        $familyMember = FamilyMember::where('email', $user->email)->first();

        if (!$familyMember) {
            return redirect('/')
                ->withErrors(['family' => 'No linked family record found for this user.']);
        }

        $patient = Patient::find($familyMember->patient_id);

        $appointments = Appointment::where('patient_id', $patient->id ?? null)
            ->latest()
            ->get();

        $prescriptions = Prescription::where('patient_id', $patient->id ?? null)
            ->latest()
            ->get();

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
