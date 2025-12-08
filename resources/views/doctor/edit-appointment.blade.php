{{-- resources/views/doctor/edit-appointment.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Appointment')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Appointment</h1>

<x-card>
    <form method="POST" action="{{ route('appointments.update', $appointment) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-semibold mb-1">Patient</label>
            <select name="patient_id" class="border rounded w-full px-2 py-1 text-sm">
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}"
                        {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="date"
                   value="{{ old('date', optional($appointment->date)->format('Y-m-d')) }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Time</label>
            <input type="time" name="time"
                   value="{{ old('time', $appointment->time) }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Notes</label>
            <textarea name="notes" rows="3"
                      class="border rounded w-full px-2 py-1 text-sm">{{ old('notes', $appointment->notes) }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('doctor.dashboard') }}"
               class="px-3 py-2 rounded border text-sm">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                Save Changes
            </button>
        </div>
    </form>
</x-card>
@endsection
