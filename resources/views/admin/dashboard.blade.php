@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-bold mb-2">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-card title="Total Patients">
            <p class="text-2xl font-semibold">{{ $patientCount ?? 0 }}</p>
        </x-card>

        <x-card title="Employees">
            <p class="text-2xl font-semibold">{{ $employeeCount ?? 0 }}</p>
        </x-card>

        <x-card title="Active Rosters Today">
            <p class="text-2xl font-semibold">{{ $rosterTodayCount ?? 0 }}</p>
        </x-card>

        <x-card title="Outstanding Payments">
            <p class="text-xl font-semibold">${{ number_format($outstandingTotal ?? 0, 2) }}</p>
        </x-card>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-card title="Recent Missed Activities">
            <table class="w-full text-sm">
                <thead class="border-b">
                <tr class="text-left">
                    <th class="py-2">Patient</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Responsible</th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentMissedActivities ?? [] as $activity)
                    <tr class="border-b last:border-0">
                        <td class="py-2">{{ $activity->patient->name ?? 'N/A' }}</td>
                        <td>{{ $activity->type ?? '-' }}</td>
                        <td>{{ $activity->missed_at?->format('Y-m-d H:i') }}</td>
                        <td>{{ $activity->responsibleUser->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-3 text-center text-gray-500">
                            No missed activities recently.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </x-card>

        <x-card title="Quick Actions">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.employees.create') }}"
                   class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                    + Add Employee
                </a>
                <a href="{{ route('admin.patients.create') }}"
                   class="px-3 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">
                    + Add Patient
                </a>
                <a href="{{ route('rosters.create') }}"
                   class="px-3 py-2 rounded bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                    New Roster
                </a>
                <a href="{{ route('payments.index') }}"
                   class="px-3 py-2 rounded bg-yellow-500 text-white text-sm hover:bg-yellow-600">
                    View Payments
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection
