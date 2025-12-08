{{-- resources/views/family/view-prescription.blade.php --}}
@extends('layouts.app')

@section('title', 'Prescription Details')

@section('content')
<h1 class="text-2xl font-bold mb-4">Prescription Details</h1>

<x-card>
    <p class="text-sm mb-1"><strong>Medication:</strong> {{ $prescription->medication }}</p>
    <p class="text-sm mb-1"><strong>Dosage:</strong> {{ $prescription->dosage }}</p>
    <p class="text-sm mb-1"><strong>Instructions:</strong> {{ $prescription->instructions ?? '-' }}</p>

    <div class="flex justify-end mt-4">
        <a href="{{ route('family.dashboard') }}"
           class="px-3 py-2 rounded border text-sm">
            Back
        </a>
    </div>
</x-card>
@endsection
