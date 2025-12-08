{{-- resources/views/admin/reports.blade.php --}}
@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<h1 class="text-2xl font-bold mb-4">System Reports</h1>

<x-card>
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

    <div class="mt-3">
        {{ $reports->links() }}
    </div>
</x-card>
@endsection
