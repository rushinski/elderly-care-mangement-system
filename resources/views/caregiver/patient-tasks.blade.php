{{-- resources/views/caregiver/patient-tasks.blade.php --}}
@extends('layouts.app')

@section('title', 'Patient Tasks')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Patient Daily Chart</h1>

    @php
        $dateDisplay = \Carbon\Carbon::parse($selectedDate)->format('m/d/Y');
        $task = fn(string $type) => $tasks[$type] ?? null;
    @endphp

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

        {{-- Date (read-only text, link back to dashboard to change) --}}
        <div class="border rounded-lg p-4 bg-blue-600 text-white">
            <div class="text-sm font-semibold">Date</div>
            <div class="mt-2 text-xl font-bold">
                {{ $dateDisplay }}
            </div>
            <p class="mt-1 text-[11px] text-blue-100">
                To change the date, go back to the caregiver dashboard.
            </p>
        </div>
    </div>

    {{-- Tasks form --}}
    <form method="POST"
          action="{{ route('caregiver.patient.updateTasks', ['patient' => $patient->id]) }}">
        @csrf
        <input type="hidden" name="date" value="{{ $selectedDate }}">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-200 text-left">
                <tr>
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
                    {{-- Morning --}}
                    <td class="py-3 px-2 text-center">
                        <input type="checkbox"
                               name="{{ \App\Models\DailyTask::TASK_MORNING_MEDICINE }}"
                               @checked(optional($task(\App\Models\DailyTask::TASK_MORNING_MEDICINE))->completed)>
                    </td>

                    {{-- Afternoon --}}
                    <td class="py-3 px-2 text-center">
                        <input type="checkbox"
                               name="{{ \App\Models\DailyTask::TASK_AFTERNOON_MEDICINE }}"
                               @checked(optional($task(\App\Models\DailyTask::TASK_AFTERNOON_MEDICINE))->completed)>
                    </td>

                    {{-- Night --}}
                    <td class="py-3 px-2 text-center">
                        <input type="checkbox"
                               name="{{ \App\Models\DailyTask::TASK_NIGHT_MEDICINE }}"
                               @checked(optional($task(\App\Models\DailyTask::TASK_NIGHT_MEDICINE))->completed)>
                    </td>

                    {{-- Breakfast --}}
                    <td class="py-3 px-2 text-center">
                        <input type="checkbox"
                               name="{{ \App\Models\DailyTask::TASK_BREAKFAST }}"
                               @checked(optional($task(\App\Models\DailyTask::TASK_BREAKFAST))->completed)>
                    </td>

                    {{-- Lunch --}}
                    <td class="py-3 px-2 text-center">
                        <input type="checkbox"
                               name="{{ \App\Models\DailyTask::TASK_LUNCH }}"
                               @checked(optional($task(\App\Models\DailyTask::TASK_LUNCH))->completed)>
                    </td>

                    {{-- Dinner --}}
                    <td class="py-3 px-2 text-center">
                        <input type="checkbox"
                               name="{{ \App\Models\DailyTask::TASK_DINNER }}"
                               @checked(optional($task(\App\Models\DailyTask::TASK_DINNER))->completed)>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between items-center">
            <a href="{{ route('caregiver.dashboard', ['date' => $selectedDate]) }}"
               class="text-xs text-blue-600 hover:underline">
                &larr; Back to dashboard
            </a>

            <button type="submit"
                    class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">
                Save Updates
            </button>
        </div>
    </form>
@endsection
