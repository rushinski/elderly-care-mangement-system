{{-- resources/views/caregiver/view-task.blade.php --}}
@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<h1 class="text-2xl font-bold mb-4">Task Details</h1>

<x-card>
    <p class="text-sm mb-1">
        <strong>Patient:</strong> {{ $task->patient->name ?? 'N/A' }}
    </p>
    <p class="text-sm mb-1">
        <strong>Description:</strong> {{ $task->description }}
    </p>
    <p class="text-sm mb-1">
        <strong>Status:</strong> {{ ucfirst($task->status) }}
    </p>
    <p class="text-xs text-gray-500 mt-2">
        Created: {{ $task->created_at?->format('Y-m-d H:i') }},
        Updated: {{ $task->updated_at?->format('Y-m-d H:i') }}
    </p>

    <div class="flex justify-end gap-2 mt-4">
        <a href="{{ route('caregiver.dashboard') }}"
           class="px-3 py-2 rounded border text-sm">
            Back
        </a>
        <a href="{{ route('tasks.edit', $task) }}"
           class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
            Edit
        </a>
    </div>
</x-card>
@endsection
