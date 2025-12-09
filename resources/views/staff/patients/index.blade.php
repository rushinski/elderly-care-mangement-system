{{-- resources/views/staff/patients/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Patient Directory & Management')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Patient Directory &amp; Management</h1>

    @if(session('success'))
        <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 px-4 py-2 rounded bg-red-100 text-red-800 text-sm">
            <ul class="list-disc pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ============================
         TOP: Search by Patient ID
         ============================ --}}
    <x-card title="Search Patient by ID">
        <form method="GET" action="{{ route('staff.patients.index') }}" class="flex flex-col md:flex-row gap-3 mb-4">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1" for="patient_id">
                    Patient ID / Code
                </label>
                <input
                    type="text"
                    id="patient_id"
                    name="patient_id"
                    value="{{ old('patient_id', $patientIdSearch) }}"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-400"
                    placeholder="Enter patient ID or code"
                >
            </div>

            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700"
                >
                    Search
                </button>

                @if($patientIdSearch)
                    <a href="{{ route('staff.patients.index') }}"
                       class="px-3 py-2 rounded bg-gray-100 text-gray-700 text-xs hover:bg-gray-200">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        @if($patientIdSearch && !$selectedPatient)
            <p class="text-sm text-red-600">
                No patient found for ID/code: <strong>{{ $patientIdSearch }}</strong>
            </p>
        @endif

        @if($selectedPatient)
            <div class="border-t pt-4 mt-2">
                <h2 class="text-sm font-semibold mb-3">
                    Patient Details
                </h2>

                <form
                    method="POST"
                    action="{{ route('staff.patients.update', $selectedPatient) }}"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4"
                >
                    @csrf
                    @method('PUT')

                    {{-- Patient ID (read-only) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Patient ID / Code
                        </label>
                        <input
                            type="text"
                            class="w-full border rounded px-3 py-2 text-sm bg-gray-100"
                            value="{{ $selectedPatient->patient_code ?? $selectedPatient->id }}"
                            disabled
                        >
                    </div>

                    {{-- Name (read-only) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Name
                        </label>
                        <input
                            type="text"
                            class="w-full border rounded px-3 py-2 text-sm bg-gray-100"
                            value="{{ $selectedPatient->user?->full_name ?? 'N/A' }}"
                            disabled
                        >
                    </div>

                    {{-- Group (editable for Admin/Supervisor only) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1" for="group">
                            Group
                        </label>
                        <input
                            type="text"
                            id="group"
                            name="group"
                            class="w-full border rounded px-3 py-2 text-sm {{ $canEdit ? 'bg-white' : 'bg-gray-100' }}"
                            value="{{ old('group', $selectedPatient->group) }}"
                            {{ $canEdit ? '' : 'disabled' }}
                        >
                    </div>

                    {{-- Admission Date (editable for Admin/Supervisor only) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1" for="admission_date">
                            Admission Date
                        </label>
                        <input
                            type="date"
                            id="admission_date"
                            name="admission_date"
                            class="w-full border rounded px-3 py-2 text-sm {{ $canEdit ? 'bg-white' : 'bg-gray-100' }}"
                            value="{{ old('admission_date', optional($selectedPatient->admission_date)->format('Y-m-d')) }}"
                            {{ $canEdit ? '' : 'disabled' }}
                        >
                    </div>

                    {{-- Only show Save button if user can edit --}}
                    @if($canEdit)
                        <div class="md:col-span-2 flex justify-end">
                            <button
                                type="submit"
                                class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700"
                            >
                                Save Changes
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        @endif
    </x-card>

    {{-- ============================
         BOTTOM: Patient Directory
         ============================ --}}
    <x-card title="Patient Directory">
        <form method="GET" action="{{ route('staff.patients.index') }}" class="flex flex-col md:flex-row gap-3 mb-4">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1" for="search">
                    Search (ID, name, age, emergency contact, admission date, group)
                </label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ old('search', $directorySearch) }}"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-400"
                    placeholder="Type any patient info to search..."
                >
            </div>

            <div class="flex items-end gap-2">
                {{-- preserve patient_id if currently focused on a specific patient --}}
                @if($patientIdSearch)
                    <input type="hidden" name="patient_id" value="{{ $patientIdSearch }}">
                @endif

                <button
                    type="submit"
                    class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700"
                >
                    Search
                </button>

                <a href="{{ route('staff.patients.index') }}"
                   class="px-3 py-2 rounded bg-gray-100 text-gray-700 text-xs hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b bg-gray-50">
                <tr class="text-left">
                    <th class="py-2 px-2">ID</th>
                    <th class="py-2 px-2">Name</th>
                    <th class="py-2 px-2">Age</th>
                    <th class="py-2 px-2">Group</th>
                    <th class="py-2 px-2">Emergency Contact</th>
                    <th class="py-2 px-2">Emergency Relation</th>
                    <th class="py-2 px-2">Admission Date</th>
                </tr>
                </thead>
                <tbody>
                @forelse($patients as $patient)
                    @php
                        $user = $patient->user;
                    @endphp
                    <tr class="border-b last:border-0 hover:bg-gray-50">
                        <td class="py-2 px-2">
                            {{ $patient->patient_code ?? $patient->id }}
                        </td>
                        <td class="py-2 px-2">
                            {{ $user?->full_name ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-2">
                            {{ $user?->age !== null ? $user->age : 'N/A' }}
                        </td>
                        <td class="py-2 px-2">
                            {{ $patient->group ?? '-' }}
                        </td>
                        <td class="py-2 px-2">
                            {{ $patient->emergency_contact ?? '-' }}
                        </td>
                        <td class="py-2 px-2">
                            {{ $patient->emergency_contact_relation ?? '-' }}
                        </td>
                        <td class="py-2 px-2">
                            {{ optional($patient->admission_date)->format('Y-m-d') ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">
                            No patients found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $patients->links() }}
        </div>
    </x-card>
@endsection
