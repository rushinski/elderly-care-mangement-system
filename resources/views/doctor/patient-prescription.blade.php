@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 1050px;">

    {{-- PAGE TITLE --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-2">Patient Prescription Management</h3>
        <div class="text-muted">{{ now()->format('F j, Y') }}</div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    {{-- PATIENT INFO --}}
    <div class="border rounded-4 p-4 shadow-sm mb-4 bg-white">
        <h5 class="fw-semibold mb-3">Patient Information</h5>

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="lh-lg">
                <div><strong>Patient:</strong> {{ $patient->user->name }}</div>
                <div><strong>Doctor:</strong> {{ auth()->user()->name }}</div>
            </div>

            <span class="badge bg-info text-dark px-4 py-2 fs-6 rounded-pill">Active Patient Record</span>
        </div>
    </div>

    {{-- PREVIOUS PRESCRIPTIONS --}}
    <div class="border rounded-4 p-4 shadow-sm mb-4 bg-white">
        <h5 class="fw-semibold mb-3">Previous Prescriptions</h5>

        @if($prescriptions->isEmpty())
            <div class="text-muted text-center py-2">No previous prescriptions found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle" style="min-width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 20%;">Date</th>
                            <th style="width: 40%;">Comment</th>
                            <th class="text-center" style="width: 15%;">Morning</th>
                            <th class="text-center" style="width: 15%;">Afternoon</th>
                            <th class="text-center" style="width: 15%;">Night</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescriptions as $p)
                            <tr>
                                <td class="ps-3">{{ $p->created_at->format('Y-m-d') }}</td>
                                <td>{{ $p->comment ?: '—' }}</td>
                                <td class="text-center">{{ $p->morning_med ? '✔️' : '—' }}</td>
                                <td class="text-center">{{ $p->afternoon_med ? '✔️' : '—' }}</td>
                                <td class="text-center">{{ $p->night_med ? '✔️' : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- NEW PRESCRIPTION --}}
    <div class="border rounded-4 p-4 shadow-sm bg-white">

        <h5 class="fw-semibold mb-3">New Prescription</h5>

        @if($canPrescribe && isset($latestAppointment))

            <form method="POST" action="{{ route('doctor.storePrescription') }}">
                @csrf

                <div class="alert alert-info py-2 px-3 mb-4 rounded-3">
                    Only available for today’s appointment:
                    <strong>{{ \Carbon\Carbon::parse($latestAppointment->appointment_date)->format('F j, Y') }}</strong>
                </div>

                {{-- Notes --}}
                <div class="mb-4">
                    <label class="fw-semibold mb-2">Prescription Notes</label>
                    <textarea 
                        name="comment"
                        class="form-control shadow-sm"
                        rows="3"
                        placeholder="Enter instructions or medication notes..."
                    ></textarea>
                </div>

                {{-- Checkboxes --}}
                <div class="row g-4 mb-4">
                    @foreach(['Morning' => 'morning_med', 'Afternoon' => 'afternoon_med', 'Night' => 'night_med'] as $label => $name)
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 shadow-sm text-center">
                                <label class="fw-semibold">
                                    <input type="checkbox" class="form-check-input me-2" name="{{ $name }}" value="1">
                                    {{ $label }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success px-4 py-2 rounded-pill">
                        Save
                    </button>
                </div>
            </form>

        @else
            <div class="alert alert-warning text-center py-3 rounded-3 fs-6">
                New prescriptions can only be added on the appointment date.
            </div>
        @endif
    </div>

</div>
@endsection
