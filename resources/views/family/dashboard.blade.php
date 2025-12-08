@extends('layouts.app')

@section('title', 'Family Portal')

@section('content')
<h1 class="text-2xl font-bold mb-4">Family Portal</h1>

<x-card title="Access Patient Information">
    <form method="GET" action="{{ route('family.dashboard') }}"
          class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
        <div>
            <label class="block text-xs font-semibold mb-1">Patient ID</label>
            <input type="text" name="patient_id" value="{{ request('patient_id') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold mb-1">Family Code</label>
            <input type="text" name="family_code" value="{{ request('family_code') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>
        <div class="flex items-end">
            <button type="submit"
                    class="w-full px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                View
            </button>
        </div>
    </form>

    @isset($patient)
        <h2 class="text-lg font-semibold mb-2">Patient: {{ $patient->name }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-card title="Basic Info">
                <p class="text-sm"><strong>Room:</strong> {{ $patient->room ?? '-' }}</p>
                <p class="text-sm"><strong>Doctor:</strong> {{ $patient->primaryDoctor->name ?? '-' }}</p>
                <p class="text-sm"><strong>Caregiver:</strong> {{ $patient->primaryCaregiver->name ?? '-' }}</p>
            </x-card>

            <x-card title="Today’s Appointments">
                <ul class="text-sm divide-y">
                    @forelse($appointments ?? [] as $appointment)
                        <li class="py-2">
                            {{ $appointment->scheduled_at->format('H:i') }} –
                            {{ $appointment->reason ?? 'Appointment' }}
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">No appointments today.</li>
                    @endforelse
                </ul>
            </x-card>

            <x-card title="Prescriptions">
                <ul class="text-sm divide-y">
                    @forelse($prescriptions ?? [] as $prescription)
                        <li class="py-2">
                            <div class="font-semibold">{{ $prescription->medicine_name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $prescription->dosage }} – {{ $prescription->schedule }}
                            </div>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">No prescriptions.</li>
                    @endforelse
                </ul>
            </x-card>
        </div>
    @endisset
</x-card>
@endsection
