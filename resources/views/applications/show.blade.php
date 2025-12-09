{{-- resources/views/applications/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
    <x-card title="Application Details">
        <div class="space-y-2 text-sm">
            <div>
                <span class="font-semibold">Name:</span>
                {{ $application->first_name }} {{ $application->last_name }}
            </div>

            <div>
                <span class="font-semibold">Email:</span>
                {{ $application->email }}
            </div>

            <div>
                <span class="font-semibold">Phone:</span>
                {{ $application->phone ?? '-' }}
            </div>

            <div>
                <span class="font-semibold">Address:</span>
                {{ $application->address ?? '-' }}
            </div>

            <div>
                <span class="font-semibold">Role:</span>
                {{ $application->role->name ?? '-' }}
            </div>

            <div>
                <span class="font-semibold">Date of Birth:</span>
                {{ $application->date_of_birth ?? '-' }}
            </div>

            @if ($application->isPatient())
                <div class="mt-4 border-t pt-2">
                    <div>
                        <span class="font-semibold">Family Code:</span>
                        {{ $application->family_code ?? '-' }}
                    </div>
                    <div>
                        <span class="font-semibold">Emergency Contact:</span>
                        {{ $application->emergency_contact ?? '-' }}
                    </div>
                    <div>
                        <span class="font-semibold">Relation:</span>
                        {{ $application->emergency_contact_relation ?? '-' }}
                    </div>
                </div>
            @endif

            <div class="mt-4 border-t pt-2">
                <span class="font-semibold">Status:</span>
                {{ ucfirst($application->status) }}
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex items-center gap-3">
            <a href="{{ route('applications.index') }}"
               class="text-xs text-gray-600 hover:underline">
                ← Back to applications
            </a>

            @if ($application->status === 'pending')
                <form method="POST"
                      action="{{ route('applications.approve', $application) }}"
                      class="inline-block">
                    @csrf
                    <button type="submit"
                            class="px-3 py-1 text-xs rounded bg-green-600 text-white hover:bg-green-700">
                        Approve
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('applications.reject', $application) }}"
                      class="inline-block">
                    @csrf
                    <button type="submit"
                            class="px-3 py-1 text-xs rounded bg-red-600 text-white hover:bg-red-700">
                        Reject
                    </button>
                </form>
            @else
                <span class="ml-2 text-xs text-gray-500">
                    This application has already been {{ $application->status }}.
                </span>
            @endif
        </div>
    </x-card>
@endsection
