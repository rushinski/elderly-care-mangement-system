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
        User::updateOrCreate(
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
        User::updateOrCreate(
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

        // Caregiver
        User::updateOrCreate(
            ['email' => 'caregiver@ohms.test'],
            [
                'first_name'    => 'Caregiver',
                'last_name'     => 'User',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Caregiver'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        // Family
        User::updateOrCreate(
            ['email' => 'family@ohms.test'],
            [
                'first_name'    => 'Family',
                'last_name'     => 'User',
                'password'      => Hash::make('password'),
                'phone'         => $faker->phoneNumber(),
                'address'       => $faker->address(),
                'role_id'       => $roles['Family'] ?? null,
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-30 years')->format('Y-m-d'),
            ]
        );

        // ==============================
        // 2. One baseline Patient user
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
                // Age somewhere between 60 and 85
                'date_of_birth' => $faker->dateTimeBetween('-85 years', '-60 years')->format('Y-m-d'),
            ]
        );

        Patient::firstOrCreate(
            ['user_id' => $basePatientUser->id],
            [
                // We still fill these for backwards-compat, but for the new page ID is the source of truth
                'patient_code'               => 'PC-' . strtoupper(Str::random(6)),
                'family_code'                => 'FC-' . strtoupper(Str::random(6)),
                'emergency_contact'          => $faker->name(),
                'emergency_contact_relation' => 'Spouse',
                'admission_date'             => now()->subMonths(3),
                'group'                      => 'A',
                'medical_history'            => 'Hypertension, regular checkups.',
            ]
        );

        // ==============================
        // 3. Bulk Patient population
        // ==============================
        // This is what will make your directory + age search feel "real".
        // Creates ~30 patients with varying ages, contacts & dates.

        $groups = ['A', 'B', 'C', 'D'];

        for ($i = 1; $i <= 30; $i++) {
            $dob = $faker->dateTimeBetween('-95 years', '-60 years')->format('Y-m-d'); // elderly ages

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
                // Treated as "Emergency Contact Name" in your UI
                'emergency_contact_relation' => $faker->name(),
                'admission_date'             => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'group'                      => $faker->randomElement($groups),
                'medical_history'            => $faker->sentence(12),
            ]);
        }
    }
}
