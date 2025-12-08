{{-- resources/views/supervisor/create-roster.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Roster')

@section('content')
<h1 class="text-2xl font-bold mb-4">Create Roster</h1>

<x-card>
    <form method="POST" action="{{ route('rosters.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold mb-1">Staff</label>
            <select name="user_id" class="border rounded w-full px-2 py-1 text-sm">
                <option value="">Select staff...</option>
                @foreach($staff as $member)
                    <option value="{{ $member->id }}"
                        {{ old('user_id') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }} ({{ $member->role->name ?? '' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="date"
                   value="{{ old('date') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Shift</label>
            <input type="text" name="shift"
                   value="{{ old('shift') }}"
                   placeholder="Morning / Afternoon / Night"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('supervisor.dashboard') }}"
               class="px-3 py-2 rounded border text-sm">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">
                Save
            </button>
        </div>
    </form>
</x-card>
@endsection
