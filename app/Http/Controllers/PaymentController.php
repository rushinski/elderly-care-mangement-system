<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Patient;

class PaymentController extends Controller
{
    /**
     * Display all payments for admin overview.
     */
    public function index()
    {
        $payments = Payment::with('patient')->latest()->paginate(20);
        $total = Payment::sum('amount');

        return view('admin.payments.index', compact('payments', 'total'));
    }

    /**
     * Show form for creating a new payment.
     */
    public function create()
    {
        $patients = Patient::all();
        return view('admin.payments.create', compact('patients'));
    }

    /**
     * Store a new payment record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date',
        ]);

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Payment record added successfully.');
    }

    /**
     * Edit payment details.
     */
    public function edit(Payment $payment)
    {
        $patients = Patient::all();
        return view('admin.payments.edit', compact('payment', 'patients'));
    }

    /**
     * Update existing payment.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Payment record updated successfully.');
    }

    /**
     * Delete a payment record.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment record deleted successfully.');
    }

    /**
     * Calculate total revenue across all payments.
     */
    public function summary()
    {
        $total = Payment::sum('amount');
        $byPatient = Payment::selectRaw('patient_id, SUM(amount) as total')
            ->groupBy('patient_id')
            ->with('patient')
            ->get();

        return view('admin.payments.summary', compact('total', 'byPatient'));
    }
}
