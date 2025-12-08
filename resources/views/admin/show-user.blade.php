{{-- resources/views/admin/show-user.blade.php --}}
@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<h1 class="text-2xl font-bold mb-4">User Details</h1>

<x-card>
    <p class="text-sm mb-1"><strong>Name:</strong> {{ $user->name }}</p>
    <p class="text-sm mb-1"><strong>Email:</strong> {{ $user->email }}</p>
    <p class="text-sm mb-1"><strong>Role:</strong> {{ $user->role->name ?? '-' }}</p>
    <p class="text-xs text-gray-500 mt-2">
        Created: {{ $user->created_at?->format('Y-m-d H:i') }},
        Updated: {{ $user->updated_at?->format('Y-m-d H:i') }}
    </p>

    <div class="flex justify-end gap-2 mt-4">
        <a href="{{ route('admin.dashboard') }}"
           class="px-3 py-2 rounded border text-sm">
            Back
        </a>
        <a href="{{ route('users.edit', $user) }}"
           class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
            Edit
        </a>
    </div>
</x-card>
@endsection
