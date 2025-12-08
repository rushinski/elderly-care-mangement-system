{{-- resources/views/admin/payments.blade.php --}}
@extends('layouts.app')

@section('title', 'Payments (Admin View)')

@section('content')
<h1 class="text-2xl font-bold mb-4">Payments</h1>

<x-card>
    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Patient</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        @forelse($payments as $payment)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $payment->patient->name ?? 'N/A' }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->date?->format('Y-m-d') }}</td>
                <td>{{ $payment->description ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-3 text-center text-gray-500">
                    No payments found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>
@endsection
