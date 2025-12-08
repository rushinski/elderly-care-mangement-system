{{-- resources/views/components/sidebar.blade.php --}}
<aside
    class="bg-white border-r w-64 hidden md:flex md:flex-col"
    :class="{ 'hidden': !sidebarOpen && $screen('md') === false }"
>
    <div class="px-4 py-4 border-b">
        <span class="font-bold text-sm">Navigation</span>
    </div>

    <nav class="flex-1 overflow-y-auto px-2 py-4 text-sm space-y-1">
        @auth
            @php $role = Auth::user()->role->name ?? ''; @endphp

            @if($role === 'Admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Admin Dashboard
                </a>
                <a href="{{ route('admin.reports') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Missed Activity Reports
                </a>
                <a href="{{ route('payments.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Payments
                </a>
                <a href="{{ route('payments.summary') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Payment Summary
                </a>
                {{-- User management is accessed via buttons on the dashboard,
                     because there is no users.index route --}}
            @elseif($role === 'Supervisor')
                <a href="{{ route('supervisor.dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Supervisor Dashboard
                </a>
                <a href="{{ route('rosters.create') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    New Roster
                </a>
            @elseif($role === 'Doctor')
                <a href="{{ route('doctor.dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Doctor Dashboard
                </a>
                <a href="{{ route('appointments.create') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Schedule Appointment
                </a>
            @elseif($role === 'Caregiver')
                <a href="{{ route('caregiver.dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Caregiver Dashboard
                </a>
            @elseif($role === 'Patient')
                <a href="{{ route('patient.dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    My Schedule
                </a>
            @elseif($role === 'Family')
                <a href="{{ route('family.dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Family Dashboard
                </a>
            @endif
        @endauth
    </nav>
</aside>
