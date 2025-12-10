{{-- resources/views/admin/payments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Payment')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Payment</h1>

    @if(session('success'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Top note --}}
    <p class="text-sm text-gray-700 mb-4 max-w-xl">
        Only Admin has access to this page. Use <strong>Update</strong> to recalculate
        the bill for all patients since the last update.
    </p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: main form --}}
        <div class="lg:col-span-2">
            <x-card title="Payment Entry">
                {{-- Patient lookup --}}
                <form method="GET" action="{{ route('payments.index') }}" class="mb-4 flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Patient ID</label>
                        <input type="number"
                               name="patient_id"
                               value="{{ request('patient_id') }}"
                               class="border rounded px-2 py-1 text-sm w-32"
                               required>
                    </div>

                    <div class="pt-5">
                        <button type="submit"
                                class="px-4 py-1 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                            Ok
                        </button>
                        <a href="{{ route('payments.index') }}"
                           class="ml-2 px-4 py-1 rounded bg-gray-300 text-sm hover:bg-gray-400">
                            Cancel
                        </a>
                    </div>
                </form>

                @if($patient && $account)
                    <div class="mb-4 text-sm text-gray-700">
                        <p>
                            <span class="font-semibold">Patient:</span>
                            {{ $patient->user?->full_name ?? 'N/A' }} (ID: {{ $patient->id }})
                        </p>
                        <p>
                            <span class="font-semibold">Status:</span>
                            {{ $account->status }}
                        </p>
                    </div>

                    {{-- New payment form --}}
                    <form method="POST" action="{{ route('payments.store') }}" class="space-y-4 max-w-sm">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                        <div>
                            <label class="block text-sm font-semibold mb-1">Total Due</label>
                            <input type="text"
                                   value="{{ number_format($totalDue ?? 0, 2) }}"
                                   class="border rounded px-2 py-1 text-sm w-full bg-gray-100"
                                   readonly>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-semibold mb-1">New Payment</label>
                            <input type="number"
                                   name="amount"
                                   id="amount"
                                   step="0.01"
                                   min="0"
                                   class="border rounded px-2 py-1 text-sm w-full"
                                   required>
                            @error('amount')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                Ok
                            </button>
                            <a href="{{ route('payments.index', ['patient_id' => $patient->id]) }}"
                               class="px-4 py-2 bg-gray-300 text-sm rounded hover:bg-gray-400">
                                Cancel
                            </a>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-500">
                        Enter a valid Patient ID and press <strong>Ok</strong> to view or register payments.
                    </p>
                @endif
            </x-card>
        </div>

        {{-- Right: instructions & rules --}}
        <div class="space-y-4">
            <x-card title="How it works">
                <p class="text-sm text-gray-700 mb-2">
                    When you press <strong>Update</strong>, the system:
                </p>
                <ul class="text-sm text-gray-700 list-disc list-inside space-y-1">
                    <li>Checks today’s date vs each patient’s last update date.</li>
                    <li>If different, calculates charges between those dates.</li>
                    <li>Adds the new amount into each patient’s total due.</li>
                </ul>
            </x-card>

            <x-card title="Rates">
                <ul class="text-sm text-gray-700 list-disc list-inside space-y-1">
                    <li>$10 for every day</li>
                    <li>$50 for every appointment</li>
                    <li>$5 for every prescription / medicine</li>
                </ul>
            </x-card>

            {{-- Update button --}}
            <x-card>
                <form method="GET" action="{{ route('payments.summary') }}">
                    @csrf
                    <button type="submit"
                            class="w-full px-4 py-2 bg-green-700 text-white text-sm font-semibold rounded hover:bg-green-800">
                        Update
                    </button>
                </form>
            </x-card>
        </div>
    </div>
@endsection
