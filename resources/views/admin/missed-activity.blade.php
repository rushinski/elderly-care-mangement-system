@extends('layouts.app')

@section('title', 'Missed Patient Activity')

@section('content')
<h1 class="text-2xl font-bold mb-6">Admin’s Report</h1>

{{-- Date selector --}}
<form method="GET"
      action="{{ route('admin.missedActivity') }}"
      class="mb-4 flex items-center gap-3">

    <div>
        <label class="text-sm font-semibold block mb-1">Date</label>
        <input type="date"
               name="date"
               value="{{ $date }}"
               class="border px-2 py-1 rounded">
    </div>

    <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Go
    </button>
</form>

<p class="text-sm text-gray-600 mb-4">
    This page is accessed by Admin and Supervisor.  
    It only shows the missed activity.
</p>

<div class="overflow-x-auto">
<table class="w-full text-sm border">
    <thead class="bg-gray-100 border-b">
        <tr>
            <th class="py-2 px-2">Patient’s Name</th>
            <th class="py-2 px-2">Doctor’s Name</th>
            <th class="py-2 px-2">Doctor Appointment</th>
            <th class="py-2 px-2">Caregiver Name</th>
            <th class="py-2 px-2">Morning Medicine</th>
            <th class="py-2 px-2">Afternoon Medicine</th>
            <th class="py-2 px-2">Night Medicine</th>
            <th class="py-2 px-2">Breakfast</th>
            <th class="py-2 px-2">Lunch</th>
            <th class="py-2 px-2">Dinner</th>
        </tr>
    </thead>

    <tbody>
        @foreach($results as $row)
            <tr class="border-b">
                <td class="py-2 px-2">{{ $row['patient_name'] }}</td>
                <td class="py-2 px-2">{{ $row['doctor_name'] }}</td>
                <td class="py-2 px-2">{{ $row['doctor_appointment'] }}</td>
                <td class="py-2 px-2">{{ $row['caregiver_name'] }}</td>

                {{-- Tasks (Missing/No = red, Yes = green) --}}
                @foreach(['morning_medicine','afternoon_medicine','night_medicine','breakfast','lunch','dinner'] as $field)
                    @php
                        $val = $row[$field];
                        $isBad = in_array($val, ['No', 'Missing']);
                    @endphp

                    <td class="py-2 px-2 font-semibold {{ $isBad ? 'text-red-600' : 'text-green-600' }}">
                        {{ $val }}
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>

</table>
</div>
@endsection
