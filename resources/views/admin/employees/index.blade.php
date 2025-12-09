@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<h1 class="text-2xl font-bold mb-4">Employees</h1>

<x-card title="Employees Directory" :actions="!isset($readonly) || !$readonly ? view('admin.employees._actions') : null">
    <div class="flex flex-wrap gap-2 mb-3">
        <input
            type="text"
            id="employeeSearch"
            placeholder="Search by name or role..."
            class="border rounded px-2 py-1 text-sm flex-1 md:flex-none md:w-64"
            onkeyup="filterEmployees()"
        >
    </div>

    <table class="w-full text-sm" id="employeeTable">
        <thead class="border-b">
        <tr class="text-left">
            <th class="py-2">Name</th>
            <th>Role</th>
            <th>Facility</th>
            <th>Salary</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($employees ?? [] as $employee)
            <tr class="border-b last:border-0">
                <td class="py-2">{{ $employee->name }}</td>
                <td>{{ $employee->role->name ?? '-' }}</td>
                <td>{{ $employee->facility ?? '-' }}</td>
                <td>
                    ${{ number_format($employee->salary ?? 0, 2) }}
                </td>
                <td class="text-right">
                    @if(!isset($readonly) || !$readonly)
                        {{-- Admin Controls --}}
                        <a href="{{ route('admin.employees.edit', $employee) }}"
                           class="text-xs text-blue-600 hover:underline">
                            Edit Salary
                        </a>
                    @else
                        {{-- Supervisor View-Only Mode --}}
                        <span class="text-xs text-gray-400 italic">View Only</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-3 text-center text-gray-500">
                    No employees found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-card>

<script>
    function filterEmployees() {
        const input = document.getElementById('employeeSearch');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#employeeTable tbody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }
</script>
@endsection
