{{-- resources/views/doctor/create-appointment.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Appointment')

@section('content')
<h1 class="text-2xl font-bold mb-4">Create Appointment</h1>

<x-card>
    <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold mb-1">Patient</label>
            <select name="patient_id" class="border rounded w-full px-2 py-1 text-sm">
                <option value="">Select patient...</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}"
                        {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="date"
                   value="{{ old('date') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Time</label>
            <input type="time" name="time"
                   value="{{ old('time') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="3"
                      class="border rounded w-full px-2 py-1 text-sm">{{ old('notes') }}</textarea>
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
