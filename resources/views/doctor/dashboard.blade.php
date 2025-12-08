@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Doctor Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-card title="Today’s Appointments">
        <table class="w-full text-sm">
            <thead class="border-b">
            <tr class="text-left">
                <th class="py-2">Time</th>
                <th>Patient</th>
                <th>Reason</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($todayAppointments ?? [] as $appointment)
                <tr class="border-b last:border-0">
                    <td class="py-2">{{ $appointment->scheduled_at->format('H:i') }}</td>
                    <td>{{ $appointment->patient->name }}</td>
                    <td>{{ $appointment->reason ?? '-' }}</td>
                    <td>
                        <a href="{{ route('doctor.appointments.show', $appointment) }}"
                           class="text-blue-600 hover:underline">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-3 text-center text-gray-500">
                        No appointments scheduled for today.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </x-card>

    <x-card title="My Patients">
        <ul class="divide-y text-sm">
            @forelse($patients ?? [] as $patient)
                <li class="py-2 flex items-center justify-between">
                    <span>{{ $patient->name }}</span>
                    <a href="{{ route('doctor.patients.show', $patient) }}"
                       class="text-blue-600 hover:underline text-xs">
                        View file
                    </a>
                </li>
            @empty
                <li class="py-2 text-gray-500">No patients assigned yet.</li>
            @endforelse
        </ul>
    </x-card>
</div>
@endsection
