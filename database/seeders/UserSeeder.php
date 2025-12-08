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
        $roles = Role::all();

        foreach ($roles as $role) {
            $firstName = $role->name . ' Test';
            $lastName  = 'User';

            // Every user now needs DOB
            $dob = fake()->dateTimeBetween('-90 years', '-18 years')->format('Y-m-d');

            // Create the base User
            $user = User::create([
                'first_name'   => $firstName,
                'last_name'    => $lastName,
                'email'        => strtolower($role->name) . '@ohms.test',
                'password'     => Hash::make('password'),
                'phone'        => fake()->phoneNumber(),
                'address'      => fake()->address(),
                'role_id'      => $role->id,
                'date_of_birth'=> $dob,
            ]);

            // Only create Patient record when role = Patient
            if ($role->name === 'Patient') {
                Patient::create([
                    'user_id'                    => $user->id,
                    'patient_code'               => 'PC-' . strtoupper(Str::random(6)),
                    'family_code'                => 'FC-' . strtoupper(Str::random(6)),
                    'emergency_contact'          => fake()->name(),
                    'emergency_contact_relation' => 'Sibling',
                    'admission_date'             => now(),
                    'group'                      => null,
                    'medical_history'            => null,
                ]);
            }
        }
    }
}
