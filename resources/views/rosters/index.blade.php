{{-- resources/views/rosters/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Rosters')

@section('content')
<h1 class="text-2xl font-bold mb-4">Roster Overview</h1>

<x-card title="Full Roster List">
    {{-- Top-right "Create" button for Admin/Supervisor --}}
    @php
        $roleName = auth()->user()->role->name ?? null;
        $canManageRosters = in_array($roleName, ['Admin', 'Supervisor']);
    @endphp

    @if($canManageRosters)
        <div class="flex justify-end mb-3">
            <a href="{{ route('rosters.create') }}"
               class="px-3 py-1 text-sm rounded bg-green-600 text-white hover:bg-green-700">
                + New Roster
            </a>
        </div>
    @endif

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

            @if($canManageRosters)
                <th class="text-right">Actions</th>
            @endif
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

                @if($canManageRosters)
                    <td class="py-2 text-right whitespace-nowrap">
                        <a href="{{ route('rosters.edit', $roster) }}"
                           class="text-xs text-blue-600 hover:underline mr-2">
                            Edit
                        </a>

                        <form method="POST"
                              action="{{ route('rosters.destroy', $roster) }}"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-600 hover:underline"
                                    onclick="return confirm('Delete this roster entry?')">
                                Delete
                            </button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ $canManageRosters ? 8 : 7 }}" class="py-3 text-center text-gray-500">
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
