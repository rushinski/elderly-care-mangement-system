<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        // Make sure roles exist
        $roles = Role::pluck('id', 'name');

        // ==============================
        // 1. Core staff accounts
        // ==============================

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@ohms.test'],
            [
                'first_name'    => 'Admin',
                'last_name'     => 'User',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Admin'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        // Supervisor
        $supervisorUser = User::updateOrCreate(
            ['email' => 'supervisor@ohms.test'],
            [
                'first_name'    => 'Supervisor',
                'last_name'     => 'User',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Supervisor'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        // Doctor
        $doctorUser = User::updateOrCreate(
            ['email' => 'doctor@ohms.test'],
            [
                'first_name'    => 'Doctor',
                'last_name'     => 'User',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Doctor'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        // (Optional) generic caregiver
        User::updateOrCreate(
            ['email' => 'caregiver@ohms.test'],
            [
                'first_name'    => 'Caregiver',
                'last_name'     => 'Generic',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Caregiver'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        // Four test caregivers linked to groups A–D
        $caregiverA = User::updateOrCreate(
            ['email' => 'caregiverA@ohms.test'],
            [
                'first_name'    => 'Caregiver',
                'last_name'     => 'A',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Caregiver'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        $caregiverB = User::updateOrCreate(
            ['email' => 'caregiverB@ohms.test'],
            [
                'first_name'    => 'Caregiver',
                'last_name'     => 'B',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Caregiver'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        $caregiverC = User::updateOrCreate(
            ['email' => 'caregiverC@ohms.test'],
            [
                'first_name'    => 'Caregiver',
                'last_name'     => 'C',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Caregiver'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        $caregiverD = User::updateOrCreate(
            ['email' => 'caregiverD@ohms.test'],
            [
                'first_name'    => 'Caregiver',
                'last_name'     => 'D',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Caregiver'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        // ==============================
        // 2. Baseline Patient user
        // ==============================

        $basePatientUser = User::updateOrCreate(
            ['email' => 'patient@ohms.test'],
            [
                'first_name'    => 'John',
                'last_name'     => 'Doe',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Patient'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-85 years', '-60 years')->format('Y-m-d'),
            ]
        );

        Patient::firstOrCreate(
            ['user_id' => $basePatientUser->id],
            [
                'patient_code'               => 'PC-' . strtoupper(Str::random(6)),
                'family_code'                => 'FC-' . strtoupper(Str::random(6)),
                'emergency_contact'          => $faker->name(),
                'emergency_contact_relation' => 'Spouse',
                'admission_date'             => now()->subMonths(3),
                'group'                      => 'A', // baseline A
                'medical_history'            => 'Hypertension, regular checkups.',
            ]
        );

        // ==============================
        // 3. Bulk Patient population
        // ==============================

        $groups = ['A', 'B', 'C', 'D'];

        // Ensure at least one patient per group B, C, D for testing
        foreach (['B', 'C', 'D'] as $group) {
            $dob = $faker->dateTimeBetween('-95 years', '-60 years')->format('Y-m-d');

            $patientUser = User::updateOrCreate(
                ['email' => "patient_{$group}@ohms.test"],
                [
                    'first_name'    => 'Patient',
                    'last_name'     => $group,
                    'password'      => Hash::make('password'),
                    'phone'         => $faker->phoneNumber(),
                    'address'       => $faker->address(),
                    'role_id'       => $roles['Patient'] ?? null,
                    'date_of_birth' => $dob,
                ]
            );

            Patient::updateOrCreate(
                ['user_id' => $patientUser->id],
                [
                    'patient_code'               => 'PC-' . strtoupper(Str::random(6)),
                    'family_code'                => 'FC-' . strtoupper(Str::random(6)),
                    'emergency_contact'          => $faker->name(),
                    'emergency_contact_relation' => $faker->name(),
                    'admission_date'             => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                    'group'                      => $group,
                    'medical_history'            => $faker->sentence(12),
                ]
            );
        }

        // Additional random patients
        for ($i = 1; $i <= 20; $i++) {
            $dob = $faker->dateTimeBetween('-95 years', '-60 years')->format('Y-m-d');

            $patientUser = User::create([
                'first_name'    => $faker->firstName(),
                'last_name'     => $faker->lastName(),
                'email'         => "patient{$i}@ohms.test",
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Patient'] ?? null,
                'date_of_birth' => $dob,
            ]);

            Patient::create([
                'user_id'                    => $patientUser->id,
                'patient_code'               => 'PC-' . strtoupper(Str::random(6)),
                'family_code'                => 'FC-' . strtoupper(Str::random(6)),
                'emergency_contact'          => $faker->name(),
                'emergency_contact_relation' => $faker->name(),
                'admission_date'             => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'group'                      => $faker->randomElement($groups),
                'medical_history'            => $faker->sentence(12),
            ]);
        }
    }
}
