{{-- resources/views/supervisor/edit-roster.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Roster')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Roster</h1>

<x-card>
    <form method="POST" action="{{ route('rosters.update', $roster) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-semibold mb-1">Staff</label>
            <select name="user_id" class="border rounded w-full px-2 py-1 text-sm">
                @foreach($staff as $member)
                    <option value="{{ $member->id }}"
                        {{ old('user_id', $roster->user_id) == $member->id ? 'selected' : '' }}>
                        {{ $member->name }} ({{ $member->role->name ?? '' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="date"
                   value="{{ old('date', optional($roster->date)->format('Y-m-d')) }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Shift</label>
            <input type="text" name="shift"
                   value="{{ old('shift', $roster->shift) }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('supervisor.dashboard') }}"
               class="px-3 py-2 rounded border text-sm">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                Save Changes
            </button>
        </div>
    </form>
</x-card>
@endsection
