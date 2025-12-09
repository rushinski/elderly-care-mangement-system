@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<h1 class="text-2xl font-bold mb-4">Employees</h1>

<x-card title="Employees Directory" :actions="!isset($readonly) || !$readonly ? view('admin.employees._actions') : null">
    
    {{-- Update Employee Salary --}}
    @if(!isset($readonly) || !$readonly)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Update Employee Salary</h3>
            <form method="POST" action="{{ route('admin.employees.updateSalary') }}" id="salaryForm" class="flex flex-wrap gap-3 items-end">
                @csrf
                <div>
                    <label for="employee_id" class="block text-sm font-semibold">Employee ID</label>
                    <input type="number" name="employee_id" id="employee_id" class="border rounded px-2 py-1 text-sm w-32" required>
                </div>

                <div>
                    <label for="new_salary" class="block text-sm font-semibold">New Salary ($)</label>
                    <input type="number" name="new_salary" id="new_salary" step="0.01" class="border rounded px-2 py-1 text-sm w-32" required>
                </div>

                <div class="pt-5 flex gap-2">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-1 rounded">
                        Ok
                    </button>
                    <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-black text-sm px-4 py-1 rounded">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Search Bar --}}
    <div class="flex flex-wrap gap-2 mb-3">
        <input
            type="text"
            id="employeeSearch"
            placeholder="Search by ID, name, or role..."
            class="border rounded px-2 py-1 text-sm flex-1 md:flex-none md:w-64"
            onkeyup="filterEmployees()"
        >
    </div>

    {{-- Employee Table --}}
    <table class="w-full text-sm" id="employeeTable">
        <thead class="border-b bg-gray-50 text-gray-700 font-semibold">
        <tr class="text-left">
            <th class="py-2 px-2">ID</th>
            <th class="py-2 px-2">Name</th>
            <th class="py-2 px-2">Role</th>
            <th class="py-2 px-2">Salary</th>
            <th class="py-2 px-2 text-right">Actions</th>
        </tr>
        </thead>

        <tbody>
        @forelse($employees ?? [] as $employee)
            <tr class="border-b last:border-0 hover:bg-gray-50">
                <td class="py-2 px-2">{{ $employee->id }}</td>
                <td class="py-2 px-2">
                    {{ $employee->first_name && $employee->last_name
                        ? $employee->first_name . ' ' . $employee->last_name
                        : $employee->email }}
                </td>
                <td class="py-2 px-2">{{ $employee->role->name ?? '-' }}</td>
                <td class="py-2 px-2">${{ number_format($employee->salary ?? 0, 2) }}</td>

                <td class="py-2 px-2 text-right">
                    @if(!isset($readonly) || !$readonly)
                        <button
                            type="button"
                            class="text-xs text-blue-600 hover:underline"
                            onclick="fillSalaryForm({{ $employee->id }})">
                            Edit Salary
                        </button>
                    @else
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

    function fillSalaryForm(id) {
        const input = document.getElementById('employee_id');
        const salaryField = document.getElementById('new_salary');
        input.value = id;
        salaryField.focus();
    }
</script>
@endsection
