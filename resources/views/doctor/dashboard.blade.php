{{-- resources/views/doctor/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Doctor Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-card title="Appointments">
        <table class="w-full text-sm">
            <thead class="border-b">
            <tr class="text-left">
                <th class="py-2">Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($appointments as $appointment)
                <tr class="border-b last:border-0">
                    <td class="py-2">{{ $appointment->patient->name ?? 'N/A' }}</td>
                    <td>{{ $appointment->date?->format('Y-m-d') }}</td>
                    <td>{{ $appointment->time }}</td>
                    <td class="text-right">
                        <a href="{{ route('appointments.show', $appointment) }}"
                           class="text-xs text-blue-600 hover:underline">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-3 text-center text-gray-500">
                        No appointments.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </x-card>

    <x-card title="Recent Prescriptions">
        <table class="w-full text-sm">
            <thead class="border-b">
            <tr class="text-left">
                <th class="py-2">Patient</th>
                <th>Medication</th>
                <th>Dosage</th>
            </tr>
            </thead>
            <tbody>
            @forelse($prescriptions as $prescription)
                <tr class="border-b last:border-0">
                    <td class="py-2">{{ $prescription->patient->name ?? 'N/A' }}</td>
                    <td>{{ $prescription->medication }}</td>
                    <td>{{ $prescription->dosage }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="py-3 text-center text-gray-500">
                        No prescriptions.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </x-card>
</div>
@endsection
