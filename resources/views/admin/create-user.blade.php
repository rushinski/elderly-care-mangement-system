{{-- resources/views/admin/create-user.blade.php --}}
@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<h1 class="text-2xl font-bold mb-4">Create User</h1>

<x-card>
    <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold mb-1">Name</label>
            <input type="text" name="name"
                   value="{{ old('name') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Email</label>
            <input type="email" name="email"
                   value="{{ old('email') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Password</label>
            <input type="password" name="password"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Role</label>
            <select name="role_id" class="border rounded w-full px-2 py-1 text-sm">
                <option value="">Select role...</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.dashboard') }}"
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
