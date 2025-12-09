@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-4">Supervisor Dashboard</h1>

{{-- Quick summary boxes --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <h3 class="text-gray-600 text-sm font-medium">Total Rosters</h3>
        <p class="text-3xl font-semibold text-blue-600 mt-1">{{ $rosters->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <h3 class="text-gray-600 text-sm font-medium">Pending Reports</h3>
        <p class="text-3xl font-semibold text-yellow-600 mt-1">{{ $reports->where('status', 'pending')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <h3 class="text-gray-600 text-sm font-medium">Upcoming Appointments</h3>
        <p class="text-3xl font-semibold text-green-600 mt-1">{{ $appointmentsCount ?? 0 }}</p>
    </div>
</div>

{{-- Dashboard content --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Rosters Overview --}}
    <x-card title="Rosters Overview">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-100 border-b text-gray-700 font-semibold">
                    <tr class="text-center">
                        <th class="py-2 px-2">Supervisor</th>
                        <th class="py-2 px-2">Doctor</th>
                        <th class="py-2 px-2">Caregiver 1</th>
                        <th class="py-2 px-2">Caregiver 2</th>
                        <th class="py-2 px-2">Caregiver 3</th>
                        <th class="py-2 px-2">Caregiver 4</th>
                        <th class="py-2 px-2">Date</th>
                        <th class="py-2 px-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rosters as $roster)
                        <tr class="border-b hover:bg-gray-50 text-center">
                            <td class="py-2 px-2">{{ $roster->supervisor->name ?? 'N/A' }}</td>
                            <td class="py-2 px-2">{{ $roster->doctor->name ?? 'N/A' }}</td>
                            <td class="py-2 px-2">{{ $roster->caregiver1->name ?? 'N/A' }}</td>
                            <td class="py-2 px-2">{{ $roster->caregiver2->name ?? 'N/A' }}</td>
                            <td class="py-2 px-2">{{ $roster->caregiver3->name ?? 'N/A' }}</td>
                            <td class="py-2 px-2">{{ $roster->caregiver4->name ?? 'N/A' }}</td>
                            <td class="py-2 px-2">{{ \Carbon\Carbon::parse($roster->date)->format('Y-m-d') }}</td>
                            <td class="py-2 px-2 text-right whitespace-nowrap">
                                <a href="{{ route('rosters.edit', $roster) }}"
                                   class="text-xs text-blue-600 hover:underline mr-2">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('rosters.destroy', $roster) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-red-600 hover:underline"
                                            onclick="return confirm('Delete roster entry?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-3 text-center text-gray-500">
                                No roster entries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-right">
            <a href="{{ route('rosters.index') }}"
               class="text-sm text-blue-600 hover:underline font-medium">
               View Full Roster Page →
            </a>
        </div>
    </x-card>

    {{-- Pending Reports --}}
    <x-card title="Pending Reports">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-100 border-b text-gray-700 font-semibold">
                    <tr class="text-left">
                        <th class="py-2 px-2">ID</th>
                        <th class="py-2 px-2">Status</th>
                        <th class="py-2 px-2">Created</th>
                        <th class="py-2 px-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-2">{{ $report->id }}</td>
                            <td class="py-2 px-2 capitalize">{{ $report->status }}</td>
                            <td class="py-2 px-2">{{ \Carbon\Carbon::parse($report->created_at ?? now())->format('Y-m-d H:i') }}</td>
                            <td class="py-2 px-2 text-right">
                                <form method="POST"
                                      action="{{ route('supervisor.reviewReport', $report) }}"
                                      class="inline-flex gap-2">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="text-xs text-green-600 hover:underline">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST"
                                      action="{{ route('supervisor.reviewReport', $report) }}"
                                      class="inline-flex gap-2">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="text-xs text-red-600 hover:underline">
                                        Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 text-center text-gray-500">
                                No pending reports.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
