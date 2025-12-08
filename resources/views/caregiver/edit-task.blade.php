{{-- resources/views/caregiver/edit-task.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Daily Task')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Daily Task</h1>

<x-card>
    <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-semibold mb-1">Patient</label>
            <select name="patient_id" class="border rounded w-full px-2 py-1 text-sm">
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}"
                        {{ old('patient_id', $task->patient_id) == $patient->id ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Description</label>
            <textarea name="description" rows="3"
                      class="border rounded w-full px-2 py-1 text-sm">{{ old('description', $task->description) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Status</label>
            <select name="status" class="border rounded w-full px-2 py-1 text-sm">
                <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('caregiver.dashboard') }}"
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
