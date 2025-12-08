{{-- resources/views/admin/payments/summary.blade.php --}}
@extends('layouts.app')

@section('title', 'Payment Summary')

@section('content')
<h1 class="text-2xl font-bold mb-4">Payment Summary</h1>

<x-card title="Overall Total">
    <p class="text-2xl font-semibold">
        ${{ number_format($total, 2) }}
    </p>
</x-card>

<x-card title="Total by Patient">
    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Patient</th>
            <th>Total Paid</th>
        </tr>
        </thead>
        <tbody>
        @forelse($byPatient as $row)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $row->patient->name ?? 'N/A' }}</td>
                <td>${{ number_format($row->total, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="py-3 text-center text-gray-500">
                    No payment data.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>
@endsection
