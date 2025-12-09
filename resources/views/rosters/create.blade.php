@extends('layouts.app')

@section('title', 'New Roster')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Roster</h1>

{{-- Prevent duplicate caregivers --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const caregiverSelects = document.querySelectorAll("select[id^='caregiver_']");

    function refreshCaregiverOptions() {
        const selectedValues = Array.from(caregiverSelects)
            .map(s => s.value)
            .filter(v => v !== "");

        caregiverSelects.forEach(s => {
            Array.from(s.options).forEach(opt => {
                if (!opt.value) return; // skip placeholder

                // Disable if selected in *another* select
                if (selectedValues.includes(opt.value) && opt.value !== s.value) {
                    opt.disabled = true;
                } else {
                    opt.disabled = false;
                }
            });
        });
    }

    caregiverSelects.forEach(select => {
        select.addEventListener("change", refreshCaregiverOptions);
    });

    refreshCaregiverOptions();
});
</script>

<x-card title="Create Roster">
    <form method="POST" action="{{ route('rosters.store') }}"
          class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        {{-- Date --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="date" value="{{ old('date') }}"
                   class="border rounded w-full px-2 py-1 text-sm" required>
            @error('date')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Supervisor --}}
        <div>
            <label class="block text-xs font-semibold mb-1">Supervisor</label>
            <select name="supervisor_id" class="border rounded w-full px-2 py-1 text-sm" required>
                <option value="">Select supervisor...</option>
                @foreach($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}"
                        {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                        {{ $supervisor->name }}
                    </option>
                @endforeach
            </select>
            @error('supervisor_id')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Doctor --}}
        <div>
            <label class="block text-xs font-semibold mb-1">Doctor</label>
            <select name="doctor_id" class="border rounded w-full px-2 py-1 text-sm" required>
                <option value="">Select doctor...</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}"
                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                @endforeach
            </select>
            @error('doctor_id')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Caregivers 1–4 --}}
        @for ($i = 1; $i <= 4; $i++)
            <div>
                <label class="block text-xs font-semibold mb-1">Caregiver {{ $i }}</label>
                <select name="caregiver_{{ $i }}" id="caregiver_{{ $i }}"
                        class="border rounded w-full px-2 py-1 text-sm">
                    <option value="">Select caregiver {{ $i }}...</option>
                    @foreach($caregivers as $caregiver)
                        <option value="{{ $caregiver->id }}"
                            {{ old("caregiver_$i") == $caregiver->id ? 'selected' : '' }}>
                            {{ $caregiver->name }}
                        </option>
                    @endforeach
                </select>
                @error("caregiver_$i")
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        @endfor

        <div class="md:col-span-2 flex justify-end mt-2">
            <button type="submit"
                    class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">
                Save Roster
            </button>
        </div>
    </form>
</x-card>
@endsection
