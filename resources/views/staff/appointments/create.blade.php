@extends('layouts.app')

@section('title', "Doctor's Appointment")

@section('content')
    <h1 class="text-2xl font-bold mb-4">Doctor's Appointment</h1>

    @if (session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 text-sm rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-2 bg-red-100 text-red-800 text-sm rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-card>
        <form method="POST" action="{{ route('staff.appointments.store') }}" id="appointment-form">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

                {{-- LEFT SIDE --}}
                <div class="md:col-span-2 space-y-4">

                    {{-- Patient ID --}}
                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                        <label for="patient_id" class="w-32 font-semibold text-sm text-gray-700">
                            Patient ID
                        </label>
                        <input
                            type="number"
                            id="patient_id"
                            name="patient_id"
                            value="{{ old('patient_id', optional($prefilledPatient)->id) }}"
                            class="flex-1 border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-400"
                            placeholder="Enter patient ID"
                        >
                    </div>

                    {{-- Date --}}
                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                        <label for="appointment_date" class="w-32 font-semibold text-sm text-gray-700">
                            Date
                        </label>
                        <input
                            type="date"
                            id="appointment_date"
                            name="appointment_date"
                            value="{{ old('appointment_date', $prefilledDate) }}"
                            class="flex-1 border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-400"
                        >
                    </div>

                    {{-- Doctor dropdown --}}
                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                        <label for="doctor_id" class="w-32 font-semibold text-sm text-gray-700">
                            Doctor
                        </label>
                        <select
                            id="doctor_id"
                            name="doctor_id"
                            class="flex-1 border rounded px-3 py-2 text-sm bg-white focus:outline-none focus:ring focus:border-blue-400"
                        >
                            <option value="">Select doctor</option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="flex flex-col gap-2">
                        <label for="notes" class="font-semibold text-sm text-gray-700">
                            Notes (optional)
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            class="border rounded px-3 py-2 text-sm w-full focus:outline-none focus:ring focus:border-blue-400"
                            placeholder="Any notes for the doctor..."
                        >{{ old('notes') }}</textarea>
                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4 flex gap-3">
                        <button
                            type="submit"
                            class="px-6 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700"
                        >
                            Ok
                        </button>
                        <a
                            href="{{ url()->previous() }}"
                            class="px-6 py-2 rounded bg-gray-200 text-gray-700 text-sm hover:bg-gray-300"
                        >
                            Cancel
                        </a>
                    </div>

                    <p class="mt-4 text-xs text-gray-500">
                        This page is accessed by Admin and Supervisor. When the date is selected, the Doctor dropdown
                        only shows doctors scheduled on the roster for that day.
                    </p>
                </div>

                {{-- RIGHT SIDE: Patient Name --}}
                <div class="space-y-2">
                    <label class="block font-semibold text-sm text-gray-700">
                        Patient Name
                    </label>
                    <input
                        type="text"
                        id="patient_name"
                        class="w-full border rounded px-3 py-2 text-sm bg-gray-100"
                        value="{{ optional($prefilledPatient->user ?? null)->full_name }}"
                        readonly
                    >
                    <p class="text-xs text-gray-500">
                        The name is automatically filled after entering a valid Patient ID.
                    </p>
                </div>
            </div>
        </form>
    </x-card>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const patientIdInput   = document.getElementById('patient_id');
    const patientNameInput = document.getElementById('patient_name');
    const dateInput        = document.getElementById('appointment_date');
    const doctorSelect     = document.getElementById('doctor_id');

    const patientLookupBase = "{{ url('/staff/appointments/patient') }}";
    const doctorsByDateUrl  = "{{ route('staff.appointments.doctorsByDate') }}";

    function resetDoctors(message = 'Select doctor') {
        doctorSelect.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = message;
        doctorSelect.appendChild(opt);
    }

    // Patient lookup
    patientIdInput.addEventListener('blur', function () {
        const id = this.value.trim();
        patientNameInput.value = '';

        if (!id) return;

        fetch(`${patientLookupBase}/${encodeURIComponent(id)}`)
            .then(response => {
                if (!response.ok) throw new Error('Not found');
                return response.json();
            })
            .then(data => {
                patientNameInput.value = data.name || 'N/A';
            })
            .catch(() => {
                patientNameInput.value = 'Patient not found';
            });
    });

    // Doctors by date
    dateInput.addEventListener('change', function () {
        const date = this.value;
        resetDoctors('Loading...');

        if (!date) {
            resetDoctors('Select doctor');
            return;
        }

        fetch(`${doctorsByDateUrl}?date=${encodeURIComponent(date)}`)
            .then(response => response.json())
            .then(items => {
                if (!items.length) {
                    resetDoctors('No doctor on roster for this date');
                    return;
                }

                resetDoctors('Select doctor');
                items.forEach(doc => {
                    const opt = document.createElement('option');
                    opt.value = doc.id;
                    opt.textContent = doc.name;
                    doctorSelect.appendChild(opt);
                });
            })
            .catch(() => {
                resetDoctors('Error loading doctors');
            });
    });

    // Preload doctors if date prefilled
    if (dateInput.value) {
        dateInput.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
