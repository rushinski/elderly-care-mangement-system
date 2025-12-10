{{-- resources/views/family/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Family Member Home')

@section('content')
<h1 class="text-2xl font-bold mb-6">Family Member’s Home</h1>

{{-- Error Message --}}
@if(session('error'))
    <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">
        {{ session('error') }}
    </div>
@endif

{{-- Lookup Form --}}
<x-card>
    <form method="GET" action="{{ route('family.dashboard') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1">Family Code</label>
                <input type="text" name="family_code"
                       value="{{ request('family_code') }}"
                       class="border rounded w-full px-2 py-1 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1">Patient ID</label>
                <input type="number" name="patient_id"
                       value="{{ request('patient_id') }}"
                       class="border rounded w-full px-2 py-1 text-sm">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                OK
            </button>
            <a href="{{ route('family.dashboard') }}"
               class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                Cancel
            </a>
        </div>
    </form>
</x-card>

@if(request('family_code') && request('patient_id'))
    <x-card class="mt-4">
        <form method="GET" action="{{ route('family.dashboard') }}" class="flex items-center gap-4">

            {{-- Keep family_code + patient_id in form --}}
            <input type="hidden" name="family_code" value="{{ request('family_code') }}">
            <input type="hidden" name="patient_id" value="{{ request('patient_id') }}">

            <div>
                <label class="block text-xs font-semibold mb-1">Date</label>
                <input type="date"
                       name="date"
                       value="{{ request('date', now()->toDateString()) }}"
                       class="border rounded px-2 py-1 text-sm">
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mt-6">
                Go
            </button>

        </form>
    </x-card>
@endif


{{-- Only show results when all 3 fields are given --}}
@if(isset($results))

    <div class="mt-8">
        @if(isset($results))
            <h2 class="text-xl font-semibold mt-6 mb-2">
                Patient Details for {{ $results['date'] }}
            </h2>
        @endif


        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border">
                    <thead class="bg-gray-100 border-b">
                        <tr class="text-left">
                            <th class="p-2">Doctor’s Name</th>
                            <th class="p-2">Doctor’s Appointment</th>
                            <th class="p-2">Caregiver Name</th>
                            <th class="p-2">Morning Medicine</th>
                            <th class="p-2">Afternoon Medicine</th>
                            <th class="p-2">Night Medicine</th>
                            <th class="p-2">Breakfast</th>
                            <th class="p-2">Lunch</th>
                            <th class="p-2">Dinner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="p-2">{{ $results['doctor_name'] }}</td>
                            <td class="p-2">{{ $results['appointment_time'] }}</td>
                            <td class="p-2">{{ $results['caregiver_name'] }}</td>
                            <td class="p-2">{{ $results['morning_med'] }}</td>
                            <td class="p-2">{{ $results['afternoon_med'] }}</td>
                            <td class="p-2">{{ $results['night_med'] }}</td>
                            <td class="p-2">{{ $results['breakfast'] }}</td>
                            <td class="p-2">{{ $results['lunch'] }}</td>
                            <td class="p-2">{{ $results['dinner'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

@endif

@endsection
