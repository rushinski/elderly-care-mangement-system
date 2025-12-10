{{-- resources/views/doctor/patient-prescription.blade.php --}}
@extends('layouts.app')

@section('title', 'Patient of Doctor')

@section('content')
<div class="max-w-5xl mx-auto py-8">

    {{-- PAGE TITLE --}}
    <h1 class="text-3xl font-bold mb-6">Patient of Doctor</h1>

    {{-- PATIENT INFO CARD --}}
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-lg font-semibold mb-2">Patient Information</h2>
        <div class="text-sm space-y-1">
            <p><strong>Patient:</strong>
                {{ $patient->user->first_name }} {{ $patient->user->last_name }}
            </p>
            <p><strong>Doctor:</strong> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
            <p><span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                Active Patient Record
            </span></p>
        </div>
    </div>

    {{-- ================================
        SECTION 1 — Previous Prescriptions
    ================================= --}}
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Previous Prescriptions</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Comment</th>
                        <th class="px-4 py-2 text-center">Morning Med</th>
                        <th class="px-4 py-2 text-center">Afternoon Med</th>
                        <th class="px-4 py-2 text-center">Night Med</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $p)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $p->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ $p->comment ?: '—' }}</td>
                            <td class="px-4 py-2 text-center">{{ $p->morning_med ? 'Yes' : '—' }}</td>
                            <td class="px-4 py-2 text-center">{{ $p->afternoon_med ? 'Yes' : '—' }}</td>
                            <td class="px-4 py-2 text-center">{{ $p->night_med ? 'Yes' : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">
                                No previous prescriptions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================================
        SECTION 2 — New Prescription Button
    ================================= --}}
    <div class="mb-6">
        @if($canPrescribe)
            <a href="#new-prescription-form"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                New Prescription
            </a>
        @else
            <button class="bg-gray-400 text-white px-4 py-2 rounded text-sm cursor-not-allowed"
                    disabled>
                New Prescription
            </button>

            <p class="text-sm text-red-600 mt-2">
                Only works if today is the appointment day.
            </p>
        @endif
    </div>

    {{-- ================================
        SECTION 3 — New Prescription Form (Conditional)
    ================================= --}}
    @if($canPrescribe)
    <div id="new-prescription-form" class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">New Prescription</h2>

        <form method="POST" action="{{ route('doctor.storePrescription') }}" class="space-y-6">
            @csrf

            {{-- Hidden Fields --}}
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
            <input type="hidden" name="appointment_id" value="{{ $latestAppointment->id }}">

            {{-- Comment --}}
            <div>
                <label class="block text-sm font-medium mb-1">Comment</label>
                <textarea name="comment" rows="3"
                          class="border w-full rounded px-3 py-2 text-sm"
                          placeholder="Add notes..."></textarea>
            </div>

            {{-- Medication Checkboxes --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="border rounded-lg px-4 py-3 flex items-center justify-center gap-2">
                    <input type="checkbox" name="morning_med" value="1" class="h-4 w-4">
                    <span class="text-sm font-medium">Morning Med</span>
                </label>

                <label class="border rounded-lg px-4 py-3 flex items-center justify-center gap-2">
                    <input type="checkbox" name="afternoon_med" value="1" class="h-4 w-4">
                    <span class="text-sm font-medium">Afternoon Med</span>
                </label>

                <label class="border rounded-lg px-4 py-3 flex items-center justify-center gap-2">
                    <input type="checkbox" name="night_med" value="1" class="h-4 w-4">
                    <span class="text-sm font-medium">Night Med</span>
                </label>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded text-sm">
                    Ok
                </button>

                <a href="{{ route('doctor.dashboard') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded text-sm">
                    Cancel
                </a>
            </div>

        </form>
    </div>
    @endif

</div>
@endsection
