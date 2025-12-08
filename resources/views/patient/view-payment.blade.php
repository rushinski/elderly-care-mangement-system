{{-- resources/views/patient/view-payment.blade.php --}}
@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<h1 class="text-2xl font-bold mb-4">Payment Details</h1>

<x-card>
    <p class="text-sm mb-1"><strong>Date:</strong> {{ $payment->date?->format('Y-m-d') }}</p>
    <p class="text-sm mb-1"><strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}</p>
    <p class="text-sm mb-1"><strong>Description:</strong> {{ $payment->description ?? '-' }}</p>

    <div class="flex justify-end mt-4">
        <a href="{{ route('patient.dashboard') }}"
           class="px-3 py-2 rounded border text-sm">
            Back
        </a>
    </div>
</x-card>
@endsection
