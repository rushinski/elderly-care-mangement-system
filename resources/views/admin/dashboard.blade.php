{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <x-card title="Total Users">
        <p class="text-2xl font-semibold">
            {{ $users->count() }}
        </p>
    </x-card>

    <x-card title="Recent Reports">
        <p class="text-2xl font-semibold">
            {{ $reports->count() }}
        </p>
    </x-card>

    <x-card title="Recent Payments (Sum)">
        <p class="text-2xl font-semibold">
            ${{ number_format($payments->sum('amount'), 2) }}
        </p>
    </x-card>
</div>

<x-card title="Users">
    <div class="flex justify-between items-center mb-3">
        <span class="text-sm text-gray-600">All registered users and roles</span>
        <a href="{{ route('users.create') }}"
           class="px-3 py-1 rounded bg-blue-600 text-white text-xs hover:bg-blue-700">
            + Create User
        </a>
    </div>

    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Name</th>
            <th>Email</th>
            <th>Role</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->name ?? '-' }}</td>
                <td class="text-right">
                    <a href="{{ route('users.show', $user) }}"
                       class="text-xs text-blue-600 hover:underline">
                        View
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-3 text-center text-gray-500">
                    No users found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-card title="Recent Reports">
        <table class="w-full text-sm">
            <thead class="border-b">
            <tr class="text-left">
                <th class="py-2">ID</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
            </thead>
            <tbody>
            @forelse($reports as $report)
                <tr class="border-b last:border-0">
                    <td class="py-2">{{ $report->id }}</td>
                    <td>{{ $report->status ?? '-' }}</td>
                    <td>{{ $report->created_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="py-3 text-center text-gray-500">
                        No reports found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-3 text-right">
            <a href="{{ route('admin.reports') }}"
               class="text-xs text-blue-600 hover:underline">
                View all reports
            </a>
        </div>
    </x-card>

    <x-card title="Recent Payments">
        <table class="w-full text-sm">
            <thead class="border-b">
            <tr class="text-left">
                <th class="py-2">Patient</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @forelse($payments as $payment)
                <tr class="border-b last:border-0">
                    <td class="py-2">{{ $payment->patient->name ?? 'N/A' }}</td>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->date?->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="py-3 text-center text-gray-500">
                        No payments found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-3 text-right">
            <a href="{{ route('payments.index') }}"
               class="text-xs text-blue-600 hover:underline">
                Manage payments
            </a>
        </div>
    </x-card>
</div>
@endsection
