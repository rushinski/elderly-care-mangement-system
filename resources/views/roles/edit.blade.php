@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Role</h1>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-200 px-3 py-2 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php $isSystem = in_array($role->name, \App\Models\Role::SYSTEM_ROLES, true); @endphp

    <x-card title="Role Details">
        <form action="{{ route('roles.update', $role) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="md:col-span-2">
                <label for="name" class="block text-xs font-semibold text-gray-600 mb-1">
                    Name
                    @if($isSystem)
                        <span class="text-xs text-gray-500">(locked for system roles)</span>
                    @endif
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $role->name) }}"
                    class="w-full border rounded px-3 py-2 text-sm {{ $isSystem ? 'bg-gray-100' : 'bg-white focus:outline-none focus:ring focus:border-blue-400' }}"
                    @if($isSystem) disabled @endif
                >
            </div>

            {{-- Access Level --}}
            <div class="md:col-span-1">
                <label for="access_level" class="block text-xs font-semibold text-gray-600 mb-1">
                    Access Level
                    @if($isSystem)
                        <span class="text-xs text-gray-500">(locked for system roles)</span>
                    @endif
                </label>
                <input
                    id="access_level"
                    name="access_level"
                    type="number"
                    min="0"
                    max="100"
                    value="{{ old('access_level', $role->access_level) }}"
                    class="w-full md:w-40 border rounded px-3 py-2 text-sm {{ $isSystem ? 'bg-gray-100' : 'bg-white focus:outline-none focus:ring focus:border-blue-400' }}"
                    @if($isSystem) disabled @endif
                >
                <p class="mt-1 text-xs text-gray-500">
                    0 = highest permissions. Higher numbers = fewer permissions.
                </p>
            </div>

            <div class="md:col-span-2 flex justify-end gap-2 mt-2">
                <a href="{{ route('roles.index') }}"
                   class="px-4 py-2 rounded bg-gray-100 text-gray-700 text-sm hover:bg-gray-200">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="px-4 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </x-card>
@endsection
