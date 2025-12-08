@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Supervisor Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <x-card title="Active Employees Today">
        <p class="text-2xl font-semibold">{{ $activeEmployeesToday ?? 0 }}</p>
    </x-card>

    <x-card title="Rosters Today">
        <p class="text-2xl font-semibold">{{ $rostersToday ?? 0 }}</p>
    </x-card>

    <x-card title="Reports Pending Review">
        <p class="text-2xl font-semibold">{{ $pendingReports ?? 0 }}</p>
    </x-card>
</div>

<x-card title="Rosters By Date">
    <form method="GET" action="{{ route('rosters.index') }}" class="flex flex-wrap gap-2 mb-3">
        <input type="date" name="date" value="{{ request('date') }}"
               class="border rounded px-2 py-1 text-sm">
        <button type="submit"
                class="px-3 py-1 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
            Filter
        </button>
    </form>

    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Date</th>
            <th>Shift</th>
            <th>Caregiver</th>
            <th>Patient</th>
        </tr>
        </thead>
        <tbody>
        @forelse($rosters ?? [] as $roster)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $roster->date->format('Y-m-d') }}</td>
                <td>{{ $roster->shift ?? '-' }}</td>
                <td>{{ $roster->caregiver->name ?? '-' }}</td>
                <td>{{ $roster->patient->name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-3 text-center text-gray-500">
                    No rosters found for this date.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>
@endsection
