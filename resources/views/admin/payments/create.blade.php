{{-- resources/views/admin/payments/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Payment')

@section('content')
<h1 class="text-2xl font-bold mb-4">Create Payment</h1>

<x-card>
    <form method="POST" action="{{ route('payments.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold mb-1">Patient</label>
            <select name="patient_id" class="border rounded w-full px-2 py-1 text-sm">
                <option value="">Select patient...</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}"
                        {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Amount</label>
            <input type="number" step="0.01" min="0" name="amount"
                   value="{{ old('amount') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="date"
                   value="{{ old('date') }}"
                   class="border rounded w-full px-2 py-1 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1">Description</label>
            <textarea name="description" rows="3"
                      class="border rounded w-full px-2 py-1 text-sm">{{ old('description') }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('payments.index') }}"
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
