@extends('layouts.app')

@section('title', 'Caregiver Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Caregiver Dashboard</h1>

<x-card title="Today’s Assigned Patients">
    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Patient</th>
            <th>Room</th>
            <th>Shift</th>
        </tr>
        </thead>
        <tbody>
        @forelse($assignedPatients ?? [] as $item)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $item->patient->name }}</td>
                <td>{{ $item->patient->room ?? '-' }}</td>
                <td>{{ $item->roster->shift ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="py-3 text-center text-gray-500">
                    No patients assigned for today.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>

<x-card title="Today’s Tasks">
    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Time</th>
            <th>Patient</th>
            <th>Task</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse($tasks ?? [] as $task)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $task->scheduled_at?->format('H:i') }}</td>
                <td>{{ $task->patient->name }}</td>
                <td>{{ $task->description }}</td>
                <td>
                    <form action="{{ route('caregiver.tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button
                            type="submit"
                            class="text-xs px-2 py-1 rounded
                                   {{ $task->completed ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"
                        >
                            {{ $task->completed ? 'Completed' : 'Mark Done' }}
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-3 text-center text-gray-500">
                    No tasks scheduled for today.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>
@endsection
