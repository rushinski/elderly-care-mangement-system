{{-- resources/views/supervisor/view-roster.blade.php --}}
@extends('layouts.app')

@section('title', 'Roster Details')

@section('content')
<h1 class="text-2xl font-bold mb-4">Roster Details</h1>

<x-card>
    <p class="text-sm mb-1"><strong>Staff:</strong> {{ $roster->user->name ?? 'N/A' }}</p>
    <p class="text-sm mb-1"><strong>Date:</strong> {{ $roster->date?->format('Y-m-d') }}</p>
    <p class="text-sm mb-1"><strong>Shift:</strong> {{ $roster->shift }}</p>

    <div class="flex justify-end gap-2 mt-4">
        <a href="{{ route('supervisor.dashboard') }}"
           class="px-3 py-2 rounded border text-sm">
            Back
        </a>
        <a href="{{ route('rosters.edit', $roster) }}"
           class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
            Edit
        </a>
    </div>
</x-card>
@endsection
