{{-- resources/views/staff/patients/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Patient Directory & Management')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Patient Directory &amp; Management</h1>

    {{-- ======================================
         TOP: Additional Info (Admin + Supervisor)
         Search by patient name -> ID, Group, Admission Date
         ======================================= --}}
    @if($canEdit)
        <x-card title="Additional Patient Info (Admin &amp; Supervisor)">
            <form method="GET" action="{{ route('staff.patients.index') }}" class="flex flex-col md:flex-row gap-3 mb-4">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1" for="info_name">
                        Patient ID
                    </label>
                    <input
                        type="text"
                        id="info_name"
                        name="info_name"
                        value="{{ old('info_name', $infoNameSearch) }}"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-400"
                        placeholder="Enter patient ID"
                    >
                </div>

                <div class="flex items-end gap-2">
                    {{-- Preserve directory search where reasonable --}}
                    @if($directorySearch)
                        <input type="hidden" name="search" value="{{ $directorySearch }}">
                    @endif
                    @if($searchField)
                        <input type="hidden" name="search_field" value="{{ $searchField }}">
                    @endif

                    <button
                        type="submit"
                        class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700"
                    >
                        Search
                    </button>

                    @if($infoNameSearch)
                        <a href="{{ route('staff.patients.index') }}"
                           class="px-3 py-2 rounded bg-gray-100 text-gray-700 text-xs hover:bg-gray-200">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            @if($infoNameSearch && !$selectedPatient)
                <p class="text-sm text-red-600">
                    No patient found matching name: <strong>{{ $infoNameSearch }}</strong>
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

                        {{-- ID (read-only, patients.id is source of truth) --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                ID
                            </label>
                            <input
                                type="text"
                                class="w-full border rounded px-3 py-2 text-sm bg-gray-100"
                                value="{{ $selectedPatient->id }}"
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

                        {{-- Group (editable) --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1" for="group">
                                Group
                            </label>
                            <input
                                type="text"
                                id="group"
                                name="group"
                                class="w-full border rounded px-3 py-2 text-sm bg-white"
                                value="{{ old('group', $selectedPatient->group) }}"
                            >
                        </div>

                        {{-- Admission Date (editable) --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1" for="admission_date">
                                Admission Date
                            </label>
                            <input
                                type="date"
                                id="admission_date"
                                name="admission_date"
                                class="w-full border rounded px-3 py-2 text-sm bg-white"
                                value="{{ old('admission_date', optional($selectedPatient->admission_date)->format('Y-m-d')) }}"
                            >
                        </div>

                        <div class="md:col-span-2 flex justify-end">
                            <button
                                type="submit"
                                class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </x-card>
    @endif

    {{-- ======================================
         BOTTOM: Patient Directory (All staff)
         Dropdown-driven search by field
         ======================================= --}}
    <x-card title="Patient Directory">
        <form method="GET" action="{{ route('staff.patients.index') }}" class="flex flex-col md:flex-row gap-3 mb-4">
            {{-- Field selector --}}
            <div class="w-full md:w-64">
                <label class="block text-xs font-semibold text-gray-600 mb-1" for="search_field">
                    Search Field
                </label>
                <select
                    id="search_field"
                    name="search_field"
                    class="w-full border rounded px-3 py-2 text-sm bg-white"
                >
                    <option value="">-- Select field --</option>
                    <option value="id" {{ $searchField === 'id' ? 'selected' : '' }}>ID</option>
                    <option value="name" {{ $searchField === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="age" {{ $searchField === 'age' ? 'selected' : '' }}>Age</option>
                    <option value="emergency_contact" {{ $searchField === 'emergency_contact' ? 'selected' : '' }}>
                        Emergency Contact
                    </option>
                    <option value="emergency_contact_name" {{ $searchField === 'emergency_contact_name' ? 'selected' : '' }}>
                        Emergency Contact Name
                    </option>
                    <option value="admission_date" {{ $searchField === 'admission_date' ? 'selected' : '' }}>
                        Admission Date
                    </option>
                </select>
            </div>

            {{-- Search value --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1" for="search">
                    Search Value
                </label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ old('search', $directorySearch) }}"
                    class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-400"
                    placeholder="Enter value for selected field (e.g., 33 for Age, 2024-01-01 for Admission Date)"
                >
            </div>

            <div class="flex items-end gap-2">
                {{-- Preserve info_name search if currently used --}}
                @if($infoNameSearch)
                    <input type="hidden" name="info_name" value="{{ $infoNameSearch }}">
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
                    <th class="py-2 px-2">Emergency Contact</th>
                    <th class="py-2 px-2">Emergency Contact Name</th>
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
                            {{ $patient->id }}
                        </td>
                        <td class="py-2 px-2">
                            {{ $user?->full_name ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-2">
                            {{ $user?->age !== null ? $user->age : 'N/A' }}
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
