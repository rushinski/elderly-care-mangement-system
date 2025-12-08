@extends('layouts.app')

@section('title', 'New Roster')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Roster</h1>

<x-card title="Create Roster">
    <form method="POST" action="{{ route('rosters.store') }}"
          class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="date" value="{{ old('date') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
            @error('date')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Shift</label>
            <select name="shift" class="border rounded w-full px-2 py-1 text-sm">
                <option value="">Select shift...</option>
                <option value="Morning">Morning</option>
                <option value="Afternoon">Afternoon</option>
                <option value="Night">Night</option>
            </select>
            @error('shift')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Caregiver</label>
            <select name="caregiver_id" class="border rounded w-full px-2 py-1 text-sm">
                <option value="">Select caregiver...</option>
                @foreach($caregivers ?? [] as $caregiver)
                    <option value="{{ $caregiver->id }}"
                        {{ old('caregiver_id') == $caregiver->id ? 'selected' : '' }}>
                        {{ $caregiver->name }}
                    </option>
                @endforeach
            </select>
            @error('caregiver_id')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Patient</label>
            <select name="patient_id" class="border rounded w-full px-2 py-1 text-sm">
                <option value="">Select patient...</option>
                @foreach($patients ?? [] as $patient)
                    <option value="{{ $patient->id }}"
                        {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                @endforeach
            </select>
            @error('patient_id')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-4 flex justify-end">
            <button type="submit"
                    class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">
                Save Roster
            </button>
        </div>
    </form>
</x-card>
@endsection
