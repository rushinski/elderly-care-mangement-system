{{-- resources/views/admin/employees/_actions.blade.php --}}
<div class="flex gap-2">
    @if(Auth::user()->role->name === 'Admin')
        <a href="{{ route('admin.employees.create') }}"
           class="px-3 py-1 rounded bg-blue-600 text-white text-xs hover:bg-blue-700">
            + Add Employee
        </a>
    @endif
</div>
