@extends('layouts.app')

@section('title', 'Patient File')

@section('content')
<h1 class="text-2xl font-bold mb-4">Patient File</h1>

<x-card title="Patient Info">
    <p class="text-sm"><strong>Name:</strong> {{ $patient->name }}</p>
    <p class="text-sm"><strong>Room:</strong> {{ $patient->room ?? '-' }}</p>
    <p class="text-sm"><strong>Age:</strong> {{ $patient->age ?? '-' }}</p>
    <p class="text-sm"><strong>Admission Date:</strong> {{ $patient->admitted_at?->format('Y-m-d') }}</p>
</x-card>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-card title="Previous Prescriptions">
        <ul class="text-sm divide-y">
            @forelse($prescriptions ?? [] as $prescription)
                <li class="py-2">
                    <div class="font-semibold">{{ $prescription->medicine_name }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $prescription->dosage }} – {{ $prescription->schedule }}
                        ({{ $prescription->created_at->format('Y-m-d') }})
                    </div>
                </li>
            @empty
                <li class="py-2 text-gray-500">
                    No prescriptions yet.
                </li>
            @endforelse
        </ul>
    </x-card>

    <x-card title="Add New Prescription">
        <form method="POST" action="{{ route('doctor.prescriptions.store', $patient) }}"
              class="space-y-3">
            @csrf

            <div>
                <label class="block text-xs font-semibold mb-1">Medicine Name</label>
                <input type="text" name="medicine_name" value="{{ old('medicine_name') }}"
                       class="border rounded w-full px-2 py-1 text-sm">
                @error('medicine_name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1">Dosage</label>
                <input type="text" name="dosage" value="{{ old('dosage') }}"
                       class="border rounded w-full px-2 py-1 text-sm">
                @error('dosage')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1">Schedule</label>
                <input type="text" name="schedule" value="{{ old('schedule') }}"
                       placeholder="3x per week, 8AM / 2PM"
                       class="border rounded w-full px-2 py-1 text-sm">
                @error('schedule')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                    Save Prescription
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
