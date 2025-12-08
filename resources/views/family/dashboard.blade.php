{{-- resources/views/family/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Family Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Family Dashboard</h1>

<x-card title="Patient Info">
    <p class="text-sm mb-1"><strong>Patient:</strong> {{ $patient->name }}</p>
    <p class="text-sm mb-1"><strong>Room:</strong> {{ $patient->room ?? '-' }}</p>
</x-card>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-card title="Appointments">
        <ul class="text-sm divide-y">
            @forelse($appointments as $appointment)
                <li class="py-2 flex items-center justify-between">
                    <div>
                        {{ $appointment->date?->format('Y-m-d') }} – {{ $appointment->time }}
                    </div>
                    <a href="{{ route('family.showAppointment', $appointment) }}"
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
                    <a href="{{ route('family.showPrescription', $prescription) }}"
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
</div>
@endsection
