{{-- resources/views/doctor/create-prescription.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Prescription')

@section('content')
<h1 class="text-2xl font-bold mb-4">Create Prescription</h1>

<x-card>
    <p class="text-sm mb-4">
        Patient: <strong>{{ $patient->name }}</strong>
    </p>

    <form method="POST" action="{{ route('doctor.storePrescription') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

        <div>
            <label class="block text-xs font-semibold mb-1">Medication</label>
            <input type="text" name="medication"
                   value="{{ old('medication') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Dosage</label>
            <input type="text" name="dosage"
                   value="{{ old('dosage') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Instructions</label>
            <textarea name="instructions" rows="3"
                      class="border rounded w-full px-2 py-1 text-sm">{{ old('instructions') }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('doctor.dashboard') }}"
               class="px-3 py-2 rounded border text-sm">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">
                Save
            </button>
        </div>
    </form>
</x-card>
@endsection
