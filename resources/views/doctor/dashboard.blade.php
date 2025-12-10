{{-- resources/views/doctor/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="container mx-auto py-6">

    <h1 class="text-2xl font-bold mb-6">Doctor’s Home</h1>

    {{-- ================================
         SECTION 1 — Latest Prescriptions Per Patient
    ================================= --}}
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-3">Latest Prescriptions</h2>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-200 border-b">
                    <tr class="text-left">
                        <th class="px-4 py-2">Patient</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Comment</th>
                        <th class="px-4 py-2">Morning Med</th>
                        <th class="px-4 py-2">Afternoon Med</th>
                        <th class="px-4 py-2">Night Med</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestPrescriptions as $prescription)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                {{ $prescription->patient->user->first_name }}
                                {{ $prescription->patient->user->last_name }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $prescription->created_at->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $prescription->comment ?? '—' }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ $prescription->morning_med ? 'Yes' : '—' }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ $prescription->afternoon_med ? 'Yes' : '—' }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ $prescription->night_med ? 'Yes' : '—' }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('doctor.patient.show', $prescription->patient->id) }}"
                                   class="text-blue-600 hover:underline text-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-gray-500">
                                No prescriptions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- ================================
         SECTION 2 — Old Appointments
    ================================= --}}
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-3">Old Appointments</h2>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-200 border-b">
                    <tr class="text-left">
                        <th class="px-4 py-2">Patient</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($oldAppointments as $appt)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                {{ $appt->patient->user->first_name }}
                                {{ $appt->patient->user->last_name }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $appt->appointment_date }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $appt->appointment_time ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">
                                No past appointments.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



    {{-- ================================
         SECTION 3 — Upcoming Appointments + Till Date Filter
    ================================= --}}
    <div>
        <h2 class="text-xl font-semibold mb-4">Upcoming Appointments</h2>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('doctor.dashboard') }}"
              class="flex items-center gap-4 mb-4">
            <label class="text-sm font-medium">Till date:</label>

            <input type="date"
                   name="till_date"
                   value="{{ $tillDate }}"
                   class="border px-2 py-1 rounded text-sm">

            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                Submit
            </button>
        </form>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-200 border-b">
                    <tr class="text-left">
                        <th class="px-4 py-2">Patient</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingAppointments as $appt)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                {{ $appt->patient->user->first_name }}
                                {{ $appt->patient->user->last_name }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $appt->appointment_date }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $appt->appointment_time ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">
                                No upcoming appointments.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
