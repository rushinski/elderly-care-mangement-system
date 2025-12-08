@extends('layouts.app')

@section('title', 'My Schedule')

@section('content')
<h1 class="text-2xl font-bold mb-4">My Schedule</h1>

<x-card title="Today’s Overview">
    <p class="text-sm text-gray-600 mb-2">
        Date: {{ ($date ?? now())->format('Y-m-d') }}
    </p>

    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Time</th>
            <th>Type</th>
            <th>With</th>
            <th>Notes</th>
            <th>Done?</th>
        </tr>
        </thead>
        <tbody>
        @forelse($scheduleItems ?? [] as $item)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $item->time }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->with }}</td>
                <td>{{ $item->notes }}</td>
                <td>
                    @if($item->completed)
                        ✅
                    @else
                        ⬜
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-3 text-center text-gray-500">
                    No scheduled items for today.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-card title="Doctors">
        <ul class="text-sm divide-y">
            @forelse($doctors ?? [] as $doctor)
                <li class="py-2">{{ $doctor->name }}</li>
            @empty
                <li class="py-2 text-gray-500">No doctors assigned.</li>
            @endforelse
        </ul>
    </x-card>

    <x-card title="Caregivers">
        <ul class="text-sm divide-y">
            @forelse($caregivers ?? [] as $caregiver)
                <li class="py-2">{{ $caregiver->name }}</li>
            @empty
                <li class="py-2 text-gray-500">No caregivers assigned.</li>
            @endforelse
        </ul>
    </x-card>

    <x-card title="Prescriptions">
        <ul class="text-sm divide-y">
            @forelse($prescriptions ?? [] as $prescription)
                <li class="py-2">
                    <div class="font-semibold">{{ $prescription->medicine_name }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $prescription->dosage }} – {{ $prescription->schedule }}
                    </div>
                </li>
            @empty
                <li class="py-2 text-gray-500">No prescriptions.</li>
            @endforelse
        </ul>
    </x-card>
</div>
@endsection
