{{-- resources/views/doctor/view-appointment.blade.php --}}
@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<h1 class="text-2xl font-bold mb-4">Appointment Details</h1>

<x-card>
    <p class="text-sm mb-1"><strong>Patient:</strong> {{ $appointment->patient->name ?? 'N/A' }}</p>
    <p class="text-sm mb-1"><strong>Date:</strong> {{ $appointment->date?->format('Y-m-d') }}</p>
    <p class="text-sm mb-1"><strong>Time:</strong> {{ $appointment->time }}</p>
    <p class="text-sm mb-1"><strong>Notes:</strong> {{ $appointment->notes ?? '-' }}</p>

    <div class="flex justify-end gap-2 mt-4">
        <a href="{{ route('doctor.dashboard') }}"
           class="px-3 py-2 rounded border text-sm">
            Back
        </a>
        <a href="{{ route('appointments.edit', $appointment) }}"
           class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
            Edit
        </a>
    </div>
</x-card>
@endsection
