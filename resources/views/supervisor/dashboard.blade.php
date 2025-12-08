{{-- resources/views/supervisor/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Supervisor Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-card title="Rosters">
        <table class="w-full text-sm">
            <thead class="border-b">
            <tr class="text-left">
                <th class="py-2">Staff</th>
                <th>Date</th>
                <th>Shift</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($rosters as $roster)
                <tr class="border-b last:border-0">
                    <td class="py-2">{{ $roster->user->name ?? 'N/A' }}</td>
                    <td>{{ $roster->date?->format('Y-m-d') }}</td>
                    <td>{{ $roster->shift }}</td>
                    <td class="text-right">
                        <a href="{{ route('rosters.edit', $roster) }}"
                           class="text-xs text-blue-600 hover:underline mr-2">
                            Edit
                        </a>
                        <form method="POST"
                              action="{{ route('rosters.destroy', $roster) }}"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-600 hover:underline"
                                    onclick="return confirm('Delete roster entry?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-3 text-center text-gray-500">
                        No roster entries found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </x-card>

    <x-card title="Pending Reports">
        <table class="w-full text-sm">
            <thead class="border-b">
            <tr class="text-left">
                <th class="py-2">ID</th>
                <th>Status</th>
                <th>Created</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($reports as $report)
                <tr class="border-b last:border-0">
                    <td class="py-2">{{ $report->id }}</td>
                    <td>{{ $report->status }}</td>
                    <td>{{ $report->created_at?->format('Y-m-d H:i') }}</td>
                    <td class="text-right">
                        <form method="POST"
                              action="{{ route('supervisor.reviewReport', $report) }}"
                              class="inline-flex gap-1">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit"
                                    class="text-xs text-green-600 hover:underline">
                                Approve
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('supervisor.reviewReport', $report) }}"
                              class="inline-flex gap-1 ml-1">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit"
                                    class="text-xs text-red-600 hover:underline">
                                Reject
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-3 text-center text-gray-500">
                        No pending reports.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </x-card>
</div>
@endsection
