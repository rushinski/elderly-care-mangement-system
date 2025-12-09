{{-- resources/views/rosters/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Rosters')

@section('content')
<h1 class="text-2xl font-bold mb-4">Roster Overview</h1>

<x-card title="Full Roster List">
    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Date</th>
            <th>Supervisor</th>
            <th>Doctor</th>
            <th>Caregiver 1</th>
            <th>Caregiver 2</th>
            <th>Caregiver 3</th>
            <th>Caregiver 4</th>
        </tr>
        </thead>
        <tbody>
        @forelse($rosters as $roster)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ \Carbon\Carbon::parse($roster->date)->format('Y-m-d') }}</td>
                <td>{{ $roster->supervisor->name ?? 'N/A' }}</td>
                <td>{{ $roster->doctor->name ?? 'N/A' }}</td>
                <td>{{ $roster->caregiver1->name ?? '-' }}</td>
                <td>{{ $roster->caregiver2->name ?? '-' }}</td>
                <td>{{ $roster->caregiver3->name ?? '-' }}</td>
                <td>{{ $roster->caregiver4->name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-3 text-center text-gray-500">
                    No roster entries found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $rosters->links() }}
    </div>
</x-card>
@endsection
