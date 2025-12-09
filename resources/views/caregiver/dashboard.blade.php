{{-- resources/views/caregiver/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Caregiver Dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Caregiver Dashboard</h1>

    @php
        $dateDisplay = \Carbon\Carbon::parse($selectedDate)->format('m/d/Y');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Date card --}}
        <div class="border rounded-lg p-4 bg-blue-600 text-white">
            <div class="text-sm font-semibold">Date</div>
            <div class="mt-2 text-xl font-bold">
                {{ $dateDisplay }}
            </div>

            <form method="GET"
                  action="{{ route('caregiver.dashboard') }}"
                  class="mt-3 flex items-center gap-2 text-xs">
                <input
                    type="date"
                    name="date"
                    value="{{ $selectedDate }}"
                    class="rounded px-2 py-1 text-xs
                           bg-blue-500 border border-blue-300 text-white
                           focus:outline-none focus:ring-0 focus:border-white"
                >
                <button
                    type="submit"
                    class="px-3 py-1 rounded bg-white text-blue-700 font-semibold text-xs hover:bg-gray-100">
                    Go
                </button>
            </form>

            <p class="mt-1 text-[11px] text-blue-100">
                Shows today by default. Change date to view other days.
            </p>
        </div>

        {{-- Group card --}}
        <div class="border rounded-lg p-4 bg-blue-600 text-white">
            <div class="text-sm font-semibold">Assigned Group</div>
            <div class="mt-2 text-xl font-bold">
                @if(!$roster)
                    N/A
                @elseif(!$group)
                    Not assigned on this date
                @else
                    Group {{ $group }}
                @endif
            </div>
            <p class="mt-1 text-[11px] text-blue-100">
                Group is determined by today's roster.
            </p>
        </div>

        {{-- Roster status --}}
        <div class="border rounded-lg p-4 bg-blue-600 text-white">
            <div class="text-sm font-semibold">Roster Status</div>
            <div class="mt-2 text-xl font-bold">
                @if(!$roster)
                    No roster
                @else
                    Active
                @endif
            </div>
            <p class="mt-1 text-[11px] text-blue-100">
                If there is no roster you won't see any assigned patients.
            </p>
        </div>
    </div>

    {{-- Patients list --}}
    @if(!$roster)
        <p class="text-sm text-gray-700">
            There is no roster configured for {{ $dateDisplay }}.
        </p>
    @elseif(!$group)
        <p class="text-sm text-gray-700">
            You are not assigned to any group for {{ $dateDisplay }}.
        </p>
    @elseif($patients->isEmpty())
        <p class="text-sm text-gray-700">
            There are no patients in your group ({{ $group }}) for {{ $dateDisplay }}.
        </p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="py-2 px-2">Patient ID</th>
                    <th class="py-2 px-2">Patient Name</th>
                    <th class="py-2 px-2">Group</th>
                    <th class="py-2 px-2"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($patients as $patient)
                    <tr class="border-b last:border-0 hover:bg-gray-50">
                        <td class="py-2 px-2">{{ $patient->id }}</td>
                        <td class="py-2 px-2">
                            {{ $patient->user?->full_name ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-2">{{ $patient->group }}</td>
                        <td class="py-2 px-2 text-right">
                            <a href="{{ route('caregiver.patient.show', ['patient' => $patient->id, 'date' => $selectedDate]) }}"
                               class="px-3 py-1 rounded bg-blue-600 text-white text-xs hover:bg-blue-700">
                                Open chart
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
