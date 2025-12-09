{{-- resources/views/admin/appointments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<h1 class="text-2xl font-bold mb-4">Appointments</h1>

<x-card title="Appointments Directory">
    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Doctor</th>
            <th>Patient</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Notes</th>
            @if(!($readonly ?? false))
                <th></th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($appointments as $appointment)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $appointment->doctor->name ?? 'N/A' }}</td>
                <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                <td>{{ $appointment->appointment_time ?? '-' }}</td>
                <td>{{ ucfirst($appointment->status ?? 'Unknown') }}</td>
                <td>{{ $appointment->notes ?? '-' }}</td>
                @if(!($readonly ?? false))
                    <td class="text-right">
                        <a href="{{ route('doctor.appointments.edit', $appointment) }}"
                           class="text-xs text-blue-600 hover:underline">
                            Edit
                        </a>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-3 text-center text-gray-500">
                    No appointments found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $appointments->links() }}
    </div>
</x-card>
@endsection
