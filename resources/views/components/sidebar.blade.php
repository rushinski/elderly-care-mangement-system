{{-- resources/views/components/sidebar.blade.php --}}
<aside class="bg-white border-r w-56 hidden md:flex md:flex-col">
    <div class="px-4 py-4 border-b">
        <span class="font-bold text-sm">Navigation</span>
    </div>

    <nav class="flex-1 overflow-y-auto px-2 py-4 text-sm space-y-1">
        @auth
            @php $role = Auth::user()->role->name ?? ''; @endphp

            {{-- ================= Admin ================= --}}
            @if($role === 'Admin')
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Admin Dashboard
                </a>
                <a href="{{ route('applications.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    View Applications
                </a>
                <a href="{{ route('roles.index') }}"
                    class="block px-3 py-2 rounded hover:bg-gray-100">
                     Manage Roles
                </a>
                <a href="{{ route('admin.reports') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Reports
                </a>
                <a href="{{ route('payments.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Payments
                </a>
                <a href="{{ route('payments.summary') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Payment Summary
                </a>
                {{-- Shared Patient Directory / Management --}}
                <a href="{{ route('staff.patients.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Patient Directory
                </a>

                <a href="{{ route('rosters.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    View Roster
                </a>

            {{-- ================= Supervisor ================= --}}
            @elseif($role === 'Supervisor')
                <a href="{{ route('supervisor.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Supervisor Dashboard
                </a>

                {{-- Shared admin pages in readonly mode --}}
                <a href="{{ route('supervisor.employees') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Employees
                </a>
                <a href="{{ route('supervisor.appointments') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Appointments
                </a>
                <a href="{{ route('supervisor.reports') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Reports
                </a>

                {{-- Supervisor-only pages --}}
                <a href="{{ route('rosters.create') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Create Roster
                </a>
                {{-- Shared Patient Directory / Management --}}
                <a href="{{ route('staff.patients.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Patient Directory
                </a>

                <a href="{{ route('rosters.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    View Roster
                </a>

            {{-- ================= Doctor ================= --}}
            @elseif($role === 'Doctor')
                <a href="{{ route('doctor.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Doctor Dashboard
                </a>
                <a href="{{ route('appointments.create') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Create Appointment
                </a>
                {{-- Shared Patient Directory / Management --}}
                <a href="{{ route('staff.patients.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Patient Directory
                </a>


            {{-- ================= Caregiver ================= --}}
            @elseif($role === 'Caregiver')
                <a href="{{ route('caregiver.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Caregiver Dashboard
                </a>
                <a href="{{ route('tasks.create') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Add Daily Task
                </a>
                {{-- Shared Patient Directory / Management --}}
                <a href="{{ route('staff.patients.index') }}"
                   class="block px-3 py-2 rounded hover:bg-gray-100">
                    Patient Directory
                </a>


            {{-- ================= Patient ================= --}}
            @elseif($role === 'Patient')
                <a href="{{ route('patient.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    My Dashboard
                </a>


            {{-- ================= Family ================= --}}
            @elseif($role === 'Family')
                <a href="{{ route('family.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">
                    Family Dashboard
                </a>
            @endif
        @endauth
    </nav>
</aside>
