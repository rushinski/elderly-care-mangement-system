{{-- resources/views/admin/edit-user.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit User</h1>
{{-- <x-card>
    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-semibold mb-1">Name</label>
            <input type="text" name="name"
                   value="{{ old('name', $user->name) }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Role</label>
            <select name="role_id" class="border rounded w-full px-2 py-1 text-sm">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-between items-center">
            <form method="POST" action="{{ route('users.destroy', $user) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-3 py-2 rounded bg-red-600 text-white text-xs hover:bg-red-700"
                        onclick="return confirm('Delete this user?')">
                    Delete
                </button>
            </form>

            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-3 py-2 rounded border text-sm">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700"
                        formmethod="POST" formaction="{{ route('users.update', $user) }}">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</x-card> --}}
<x-card>
    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- fields as above ... -->

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.dashboard') }}"
               class="px-3 py-2 rounded border text-sm">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                Save Changes
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('users.destroy', $user) }}" class="mt-4 text-right">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-3 py-2 rounded bg-red-600 text-white text-xs hover:bg-red-700"
                onclick="return confirm('Delete this user?')">
            Delete User
        </button>
    </form>
</x-card>
@endsection
