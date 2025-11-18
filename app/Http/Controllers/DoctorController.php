<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Patient;

class DoctorController extends Controller
{
    /**
     * Display doctor dashboard with appointments and prescriptions.
     */
    public function index()
    {
        $appointments = Appointment::with('patient')->latest()->get();
        $prescriptions = Prescription::with('patient')->latest()->get();

        return view('doctor.dashboard', compact('appointments', 'prescriptions'));
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

        Appointment::create($validated);

        return redirect()->route('doctor.index')->with('success', 'Appointment created successfully.');
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

        $appointment->update($validated);

        return redirect()->route('doctor.index')->with('success', 'Appointment updated successfully.');
    }

    /**
     * Delete an appointment.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('doctor.index')->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Show form for adding a prescription.
     */
    public function createPrescription(Patient $patient)
    {
        return view('doctor.create-prescription', compact('patient'));
    }

    /**
     * Store a new prescription.
     */
    public function storePrescription(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medication' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'instructions' => 'nullable|string',
        ]);

        Prescription::create($validated);

        return redirect()->route('doctor.index')->with('success', 'Prescription added successfully.');
    }
}
