@extends('layouts.app')

@section('title', 'Patients')

@section('content')
<h1 class="text-2xl font-bold mb-4">Patients</h1>

<x-card title="Patients Search">
    <form method="GET" action="{{ route('admin.patients.index') }}"
          class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
        <div>
            <label class="block text-xs font-semibold mb-1">Name</label>
            <input type="text" name="name" value="{{ request('name') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold mb-1">ID</label>
            <input type="text" name="patient_id" value="{{ request('patient_id') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold mb-1">Room</label>
            <input type="text" name="room" value="{{ request('room') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>
        <div class="flex items-end">
            <button type="submit"
                    class="w-full px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                Search
            </button>
        </div>
    </form>

    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">ID</th>
            <th>Name</th>
            <th>Doctor</th>
            <th>Caregiver</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($patients ?? [] as $patient)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $patient->id }}</td>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->primaryDoctor->name ?? '-' }}</td>
                <td>{{ $patient->primaryCaregiver->name ?? '-' }}</td>
                <td class="text-right">
                    <a href="{{ route('admin.patients.show', $patient) }}"
                       class="text-xs text-blue-600 hover:underline">
                        View
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-3 text-center text-gray-500">
                    No patients found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>
@endsection
