{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card title="Register">
        <form method="POST" action="{{ route('register.post') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            {{-- LEFT SIDE: Core fields --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold mb-1">Role</label>
                    <select name="role_id" id="role_id"
                            class="border rounded w-full px-2 py-1 text-sm">
                        <option value="">Select a role...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">First Name</label>
                    <input type="text" name="first_name"
                           value="{{ old('first_name') }}"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">Last Name</label>
                    <input type="text" name="last_name"
                           value="{{ old('last_name') }}"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">Email</label>
                    <input type="email" name="email"
                           value="{{ old('email') }}"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">Phone</label>
                    <input type="text" name="phone"
                           value="{{ old('phone') }}"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">Address</label>
                    <input type="text" name="address"
                           value="{{ old('address') }}"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth"
                           value="{{ old('date_of_birth') }}"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">Password</label>
                    <input type="password" name="password"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>
            </div>

            {{-- RIGHT SIDE: Patient-only block --}}
            <div class="space-y-4 border p-4 rounded" id="patient-extra"
                 style="display: none;">

                <div>
                    <label class="block text-xs font-semibold mb-1">
                        Family Code (for Patient Family Member)
                    </label>
                    <input type="text" name="family_code"
                           value="{{ old('family_code') }}"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">Emergency Contact</label>
                    <input type="text" name="emergency_contact"
                           value="{{ old('emergency_contact') }}"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">
                        Relation to Emergency Contact
                    </label>
                    <input type="text" name="emergency_contact_relation"
                           value="{{ old('emergency_contact_relation') }}"
                           class="border rounded w-full px-2 py-1 text-sm">
                </div>

                <p class="text-xs text-gray-500">
                    This section only applies when the role is <strong>Patient</strong>.
                </p>
            </div>

            <div class="md:col-span-2 flex justify-end space-x-2">
                <button type="submit"
                        class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">
                    OK
                </button>
                <a href="{{ route('login') }}"
                   class="px-4 py-2 rounded bg-gray-400 text-white text-sm hover:bg-gray-500 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </x-card>
</div>

<script>
    const roleSelect = document.getElementById('role_id');
    const patientExtra = document.getElementById('patient-extra');

    function togglePatientFields() {
        const selectedText = roleSelect.options[roleSelect.selectedIndex]?.text || '';
        patientExtra.style.display = selectedText === 'Patient' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', togglePatientFields);
    // initial state (for validation errors / old input)
    togglePatientFields();
</script>
@endsection
