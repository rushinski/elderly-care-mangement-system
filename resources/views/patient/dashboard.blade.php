{{-- resources/views/patient/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Patient Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">My Dashboard</h1>

<x-card title="My Info">
    <p class="text-sm mb-1"><strong>Name:</strong> {{ $patient->name }}</p>
    <p class="text-sm mb-1"><strong>ID:</strong> {{ $patient->id }}</p>
    <p class="text-sm mb-1"><strong>Room:</strong> {{ $patient->room ?? '-' }}</p>
</x-card>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-card title="Appointments">
        <ul class="text-sm divide-y">
            @forelse($appointments as $appointment)
                <li class="py-2 flex items-center justify-between">
                    <div>
                        {{ $appointment->date?->format('Y-m-d') }} – {{ $appointment->time }}
                    </div>
                    <a href="{{ route('patient.showAppointment', $appointment) }}"
                       class="text-xs text-blue-600 hover:underline">
                        View
                    </a>
                </li>
            @empty
                <li class="py-2 text-gray-500">
                    No appointments.
                </li>
            @endforelse
        </ul>
    </x-card>

    <x-card title="Prescriptions">
        <ul class="text-sm divide-y">
            @forelse($prescriptions as $prescription)
                <li class="py-2 flex items-center justify-between">
                    <div>
                        {{ $prescription->medication }} ({{ $prescription->dosage }})
                    </div>
                    <a href="{{ route('patient.showPrescription', $prescription) }}"
                       class="text-xs text-blue-600 hover:underline">
                        View
                    </a>
                </li>
            @empty
                <li class="py-2 text-gray-500">
                    No prescriptions.
                </li>
            @endforelse
        </ul>
    </x-card>

    <x-card title="Payments">
        <ul class="text-sm divide-y">
            @forelse($payments as $payment)
                <li class="py-2 flex items-center justify-between">
                    <div>
                        {{ $payment->date?->format('Y-m-d') }} – ${{ number_format($payment->amount, 2) }}
                    </div>
                    <a href="{{ route('patient.showPayment', $payment) }}"
                       class="text-xs text-blue-600 hover:underline">
                        View
                    </a>
                </li>
            @empty
                <li class="py-2 text-gray-500">
                    No payments recorded.
                </li>
            @endforelse
        </ul>
    </x-card>
</div>
@endsection
