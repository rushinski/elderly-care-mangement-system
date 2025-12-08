{{-- resources/views/caregiver/create-task.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Daily Task')

@section('content')
<h1 class="text-2xl font-bold mb-4">Create Daily Task</h1>

<x-card>
    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
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
            <label class="block text-xs font-semibold mb-1">Description</label>
            <textarea name="description" rows="3"
                      class="border rounded w-full px-2 py-1 text-sm">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Status (optional)</label>
            <select name="status" class="border rounded w-full px-2 py-1 text-sm">
                <option value="">Pending (default)</option>
                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('caregiver.dashboard') }}"
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
