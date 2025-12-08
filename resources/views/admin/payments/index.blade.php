{{-- resources/views/admin/payments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<h1 class="text-2xl font-bold mb-4">Payments</h1>

<x-card>
    <div class="flex justify-between items-center mb-3">
        <p class="text-sm text-gray-600">
            Total records: {{ $payments->total() }} |
            Total amount: ${{ number_format($total, 2) }}
        </p>
        <a href="{{ route('payments.create') }}"
           class="px-3 py-2 rounded bg-green-600 text-white text-xs hover:bg-green-700">
            + Add Payment
        </a>
    </div>

    <table class="w-full text-sm">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Patient</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Description</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($payments as $payment)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $payment->patient->name ?? 'N/A' }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->date?->format('Y-m-d') }}</td>
                <td>{{ $payment->description ?? '-' }}</td>
                <td class="text-right">
                    <a href="{{ route('payments.edit', $payment) }}"
                       class="text-xs text-blue-600 hover:underline mr-2">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('payments.destroy', $payment) }}"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-xs text-red-600 hover:underline"
                                onclick="return confirm('Delete this payment?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-3 text-center text-gray-500">
                    No payments found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $payments->links() }}
    </div>
</x-card>
@endsection
