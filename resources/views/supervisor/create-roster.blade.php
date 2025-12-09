{{-- resources/views/supervisor/create-roster.blade.php --}}
@extends('layouts.app')

@section('title', 'Create New Roster')

@section('content')
<h1 class="text-2xl font-bold mb-4">Create New Roster</h1>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const caregiverSelects = document.querySelectorAll("select[id^='caregiver_']");

    caregiverSelects.forEach(select => {
        select.addEventListener("change", () => {
            const selectedValues = Array.from(caregiverSelects)
                .map(s => s.value)
                .filter(v => v !== "");

            caregiverSelects.forEach(s => {
                Array.from(s.options).forEach(opt => {
                    if (selectedValues.includes(opt.value) && opt.value !== s.value && opt.value !== "") {
                        opt.disabled = true;
                    } else {
                        opt.disabled = false;
                    }
                });
            });
        });
    });
});
</script>

<x-card title="New Roster Entry">
    <form method="POST" action="{{ route('rosters.store') }}" class="space-y-4">
        @csrf

        {{-- Date --}}
        <div>
            <label for="date" class="block text-sm font-semibold">Date</label>
            <input type="date" name="date" id="date"
                   value="{{ old('date') }}"
                   class="border rounded px-2 py-1 w-full" required>
        </div>

        {{-- Supervisor --}}
        <div>
            <label for="supervisor_id" class="block text-sm font-semibold">Supervisor</label>
            <select name="supervisor_id" id="supervisor_id" class="border rounded px-2 py-1 w-full" required>
                <option value="">-- Select Supervisor --</option>
                @foreach($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}"
                        {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                        {{ $supervisor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Doctor --}}
        <div>
            <label for="doctor_id" class="block text-sm font-semibold">Doctor</label>
            <select name="doctor_id" id="doctor_id" class="border rounded px-2 py-1 w-full" required>
                <option value="">-- Select Doctor --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}"
                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Caregivers --}}
        @for ($i = 1; $i <= 4; $i++)
            <div>
                <label for="caregiver_{{ $i }}" class="block text-sm font-semibold">Caregiver {{ $i }}</label>
                <select name="caregiver_{{ $i }}" id="caregiver_{{ $i }}" class="border rounded px-2 py-1 w-full">
                    <option value="">-- Select Caregiver {{ $i }} --</option>
                    @foreach($caregivers as $caregiver)
                        <option value="{{ $caregiver->id }}"
                            {{ old("caregiver_$i") == $caregiver->id ? 'selected' : '' }}>
                            {{ $caregiver->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endfor

        {{-- Buttons --}}
        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Create Roster
            </button>
            <a href="{{ route('supervisor.dashboard') }}"
               class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</x-card>
@endsection
