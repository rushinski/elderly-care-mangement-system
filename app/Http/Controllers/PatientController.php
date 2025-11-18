<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Payment;

class PatientController extends Controller
{
    /**
     * Display the patient dashboard with appointments, prescriptions, and payments.
     */
    public function index()
    {
        $userId = auth()->id();
        $patient = Patient::where('user_id', $userId)->firstOrFail();

        $appointments = Appointment::where('patient_id', $patient->id)->latest()->get();
        $prescriptions = Prescription::where('patient_id', $patient->id)->latest()->get();
        $payments = Payment::where('patient_id', $patient->id)->latest()->get();

        return view('patient.dashboard', compact('patient', 'appointments', 'prescriptions', 'payments'));
    }

    /**
     * Show appointment details.
     */
    public function showAppointment(Appointment $appointment)
    {
        $this->authorizePatientAccess($appointment->patient_id);
        return view('patient.view-appointment', compact('appointment'));
    }

    /**
     * Show prescription details.
     */
    public function showPrescription(Prescription $prescription)
    {
        $this->authorizePatientAccess($prescription->patient_id);
        return view('patient.view-prescription', compact('prescription'));
    }

    /**
     * Show payment details.
     */
    public function showPayment(Payment $payment)
    {
        $this->authorizePatientAccess($payment->patient_id);
        return view('patient.view-payment', compact('payment'));
    }

    /**
     * Verify that the current user owns this patient record.
     */
    private function authorizePatientAccess($patientId)
    {
        $userId = auth()->id();
        $patient = Patient::where('id', $patientId)->where('user_id', $userId)->first();

        if (!$patient) {
            abort(403, 'Unauthorized access.');
        }
    }
}
