@extends('layouts.app')

@section('title', 'Pending Applications')

@section('content')
<x-card title="Pending Applications">
    <table class="w-full text-sm">
        <thead>
        <tr class="border-b">
            <th class="text-left py-2">Name</th>
            <th class="text-left py-2">Email</th>
            <th class="text-left py-2">Role</th>
            <th class="text-left py-2">Submitted</th>
            <th class="text-left py-2"></th>
        </tr>
        </thead>
        <tbody>
        @forelse($applications as $app)
            <tr class="border-b">
                <td class="py-2">{{ $app->first_name }} {{ $app->last_name }}</td>
                <td class="py-2">{{ $app->email }}</td>
                <td class="py-2">{{ $app->role->name ?? '-' }}</td>
                <td class="py-2">{{ $app->created_at->format('Y-m-d H:i') }}</td>
                <td class="py-2">
                    <a href="{{ route('applications.show', $app) }}"
                       class="text-blue-600 text-xs hover:underline">View</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="py-4 text-center text-gray-500">No pending applications.</td></tr>
        @endforelse
        </tbody>
    </table>
</x-card>
@endsection
