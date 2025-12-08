@extends('layouts.app')

@section('title', 'Payment Summary')

@section('content')
<h1 class="text-2xl font-bold mb-4">Payment Summary</h1>

<x-card title="Patient Billing">
    <p class="text-sm"><strong>Patient:</strong> {{ $patient->name }}</p>
    <p class="text-sm"><strong>Admission Days:</strong> {{ $billing->admission_days }}</p>
    <p class="text-sm"><strong>Doctor Appointments:</strong> {{ $billing->appointment_count }}</p>
    <p class="text-sm"><strong>Medicines / Month:</strong> {{ $billing->medicine_count }}</p>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-4 text-sm">
        <div>
            <div class="font-semibold">Stay Charges</div>
            <div>${{ number_format($billing->stay_total, 2) }}</div>
        </div>
        <div>
            <div class="font-semibold">Appointments</div>
            <div>${{ number_format($billing->appointment_total, 2) }}</div>
        </div>
        <div>
            <div class="font-semibold">Medicines</div>
            <div>${{ number_format($billing->medicine_total, 2) }}</div>
        </div>
        <div>
            <div class="font-semibold">Total Due</div>
            <div class="text-lg font-bold">
                ${{ number_format($billing->grand_total, 2) }}
            </div>
        </div>
    </div>
</x-card>

<x-card title="Record Payment">
    <form method="POST" action="{{ route('payments.store', $patient) }}"
          class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold mb-1">Amount</label>
            <input type="number" step="0.01" min="0"
                   name="amount" value="{{ old('amount') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
            @error('amount')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Method</label>
            <select name="method" class="border rounded w-full px-2 py-1 text-sm">
                <option value="">Select method...</option>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="insurance">Insurance</option>
            </select>
            @error('method')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="paid_at" value="{{ old('paid_at', now()->toDateString()) }}"
                   class="border rounded w-full px-2 py-1 text-sm">
            @error('paid_at')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-3 flex justify-end">
            <button type="submit"
                    class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">
                Save Payment
            </button>
        </div>
    </form>
</x-card>

<x-card title="Payment History">
    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Date</th>
            <th>Amount</th>
            <th>Method</th>
        </tr>
        </thead>
        <tbody>
        @forelse($payments ?? [] as $payment)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $payment->paid_at?->format('Y-m-d') }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ ucfirst($payment->method) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="py-3 text-center text-gray-500">
                    No payments recorded yet.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>
@endsection
