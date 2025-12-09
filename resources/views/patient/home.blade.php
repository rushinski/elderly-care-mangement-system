{{-- resources/views/patient/home.blade.php --}}
@extends('layouts.app')

@section('title', "Patient's Home")

@section('content')
    <h1 class="text-2xl font-bold mb-6">Patient's Home</h1>

    {{-- Top details --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        {{-- Patient ID --}}
        <div class="border rounded-lg p-4 bg-blue-600 text-white">
            <div class="text-sm font-semibold">Patient ID</div>
            <div class="mt-2 text-xl font-bold">
                {{ $patient->id }}
            </div>
        </div>

        {{-- Patient Name --}}
        <div class="border rounded-lg p-4 bg-blue-600 text-white">
            <div class="text-sm font-semibold">Patient Name</div>
            <div class="mt-2 text-xl font-bold">
                {{ $patient->user?->full_name ?? 'N/A' }}
            </div>
        </div>

        @php
            // Nice display format for the date card
            $dateDisplay = \Carbon\Carbon::parse($selectedDate)->format('m/d/Y');
        @endphp

        {{-- Date --}}
        <div class="border rounded-lg p-4 bg-blue-600 text-white">
            <div class="text-sm font-semibold">Date</div>

            {{-- Big text, like Patient ID / Name --}}
            <div class="mt-2 text-xl font-bold">
                {{ $dateDisplay }}
            </div>

            {{-- Small inline form to change the date --}}
            <form method="GET"
                action="{{ route('patient.dashboard') }}"
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
                    class="px-3 py-1 rounded bg-white text-blue-700 font-semibold text-xs hover:bg-gray-100"
                >
                    Go
                </button>
            </form>
        </div>
    </div>

    {{-- Main table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
            <tr class="bg-gray-200 text-left">
                <th class="py-3 px-2">Doctor's Name</th>
                <th class="py-3 px-2">Doctor's Appointment</th>
                <th class="py-3 px-2">Caregiver's Name</th>
                <th class="py-3 px-2">Morning Medicine</th>
                <th class="py-3 px-2">Afternoon Medicine</th>
                <th class="py-3 px-2">Night Medicine</th>
                <th class="py-3 px-2">Breakfast</th>
                <th class="py-3 px-2">Lunch</th>
                <th class="py-3 px-2">Dinner</th>
            </tr>
            </thead>
            <tbody>
            <tr class="bg-gray-50">
                {{-- Doctor's Name --}}
                <td class="py-3 px-2 align-middle">
                    @if($appointment && $appointment->doctor?->user)
                        {{ $appointment->doctor->user->full_name }}
                    @else
                        <span class="text-gray-500">No doctor assigned</span>
                    @endif
                </td>

                {{-- Doctor's Appointment --}}
                <td class="py-3 px-2 align-middle">
                    @if($appointment)
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}
                    @else
                        <span class="text-gray-500 italic">No appointment for this day</span>
                    @endif
                </td>

                {{-- Caregiver's Name --}}
                <td class="py-3 px-2 align-middle">
                    {{ $caregiverName ?? 'N/A' }}
                </td>

                {{-- Checkbox helper --}}
                @php
                    $task = fn(string $type) => $tasks[$type] ?? null;
                @endphp

                {{-- Morning Medicine --}}
                <td class="py-3 px-2 text-center">
                    <input type="checkbox"
                           disabled
                           @checked(optional($task(\App\Models\DailyTask::TASK_MORNING_MEDICINE))->completed) >
                </td>

                {{-- Afternoon Medicine --}}
                <td class="py-3 px-2 text-center">
                    <input type="checkbox"
                           disabled
                           @checked(optional($task(\App\Models\DailyTask::TASK_AFTERNOON_MEDICINE))->completed) >
                </td>

                {{-- Night Medicine --}}
                <td class="py-3 px-2 text-center">
                    <input type="checkbox"
                           disabled
                           @checked(optional($task(\App\Models\DailyTask::TASK_NIGHT_MEDICINE))->completed) >
                </td>

                {{-- Breakfast --}}
                <td class="py-3 px-2 text-center">
                    <input type="checkbox"
                           disabled
                           @checked(optional($task(\App\Models\DailyTask::TASK_BREAKFAST))->completed) >
                </td>

                {{-- Lunch --}}
                <td class="py-3 px-2 text-center">
                    <input type="checkbox"
                           disabled
                           @checked(optional($task(\App\Models\DailyTask::TASK_LUNCH))->completed) >
                </td>

                {{-- Dinner --}}
                <td class="py-3 px-2 text-center">
                    <input type="checkbox"
                           disabled
                           @checked(optional($task(\App\Models\DailyTask::TASK_DINNER))->completed) >
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <p class="mt-6 text-xs text-gray-600 border-t pt-3">
        Your caregiver will update the status from their own dashboard.
    </p>
@endsection
