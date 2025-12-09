@extends('layouts.app')

@section('title', 'Manage Roles')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Manage Roles</h1>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-200 px-3 py-2 rounded">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-200 px-3 py-2 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-card title="Roles">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
            <div class="text-xs text-gray-600">
                <p class="font-semibold">Access Level Key</p>
                <p>0 = highest permissions (Admin). Higher numbers = fewer permissions.</p>
            </div>

            <a href="{{ route('roles.create') }}"
               class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700 text-center">
                + Create Role
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b bg-gray-50">
                <tr class="text-left">
                    <th class="py-2 px-2">ID</th>
                    <th class="py-2 px-2">Name</th>
                    <th class="py-2 px-2">Access Level</th>
                    <th class="py-2 px-2">Created At</th>
                    <th class="py-2 px-2 text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($roles as $role)
                    @php
                        $isSystem = in_array($role->name, \App\Models\Role::SYSTEM_ROLES, true);
                    @endphp
                    <tr class="border-b last:border-0 hover:bg-gray-50">
                        <td class="py-2 px-2">{{ $role->id }}</td>
                        <td class="py-2 px-2">
                            {{ $role->name }}
                            @if($isSystem)
                                <span class="ml-1 text-[10px] uppercase text-gray-500">system</span>
                            @endif
                        </td>
                        <td class="py-2 px-2">
                            {{ $role->access_level }}
                        </td>
                        <td class="py-2 px-2">
                            {{ $role->created_at?->format('Y-m-d H:i') }}
                        </td>
                        <td class="py-2 px-2 text-right space-x-2">
                            <a href="{{ route('roles.edit', $role) }}"
                               class="text-xs text-blue-600 hover:underline">
                                Edit
                            </a>

                            @unless($isSystem)
                                <form action="{{ route('roles.destroy', $role) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">
                            No roles found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
@endsection
