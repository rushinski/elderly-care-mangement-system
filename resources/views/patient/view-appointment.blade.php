{{-- resources/views/patient/view-appointment.blade.php --}}
@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<h1 class="text-2xl font-bold mb-4">Appointment Details</h1>

<x-card>
    <p class="text-sm mb-1"><strong>Date:</strong> {{ $appointment->date?->format('Y-m-d') }}</p>
    <p class="text-sm mb-1"><strong>Time:</strong> {{ $appointment->time }}</p>
    <p class="text-sm mb-1"><strong>Notes:</strong> {{ $appointment->notes ?? '-' }}</p>

    <div class="flex justify-end mt-4">
        <a href="{{ route('patient.dashboard') }}"
           class="px-3 py-2 rounded border text-sm">
            Back
        </a>
    </div>
</x-card>
@endsection
