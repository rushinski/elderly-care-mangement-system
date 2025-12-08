{{-- resources/views/caregiver/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Caregiver Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Caregiver Dashboard</h1>

<x-card title="Assigned Patients">
    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Patient</th>
            <th>Date</th>
            <th>Shift</th>
        </tr>
        </thead>
        <tbody>
        @forelse($assignedPatients as $assignment)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $assignment->patient->name ?? 'N/A' }}</td>
                <td>{{ $assignment->date?->format('Y-m-d') }}</td>
                <td>{{ $assignment->shift ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="py-3 text-center text-gray-500">
                    No assigned patients.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>

<x-card title="Daily Tasks">
    <div class="mb-3 text-right">
        <a href="{{ route('tasks.create') }}"
           class="px-3 py-2 rounded bg-green-600 text-white text-xs hover:bg-green-700">
            + Add Task
        </a>
    </div>

    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Patient</th>
            <th>Description</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($tasks as $task)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $task->patient->name ?? 'N/A' }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ ucfirst($task->status) }}</td>
                <td class="text-right">
                    <a href="{{ route('tasks.show', $task) }}"
                       class="text-xs text-blue-600 hover:underline mr-2">
                        View
                    </a>
                    <a href="{{ route('tasks.edit', $task) }}"
                       class="text-xs text-blue-600 hover:underline mr-2">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-xs text-red-600 hover:underline"
                                onclick="return confirm('Delete this task?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-3 text-center text-gray-500">
                    No tasks.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>
@endsection
