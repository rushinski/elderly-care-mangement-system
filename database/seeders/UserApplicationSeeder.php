<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserApplication;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            $firstName = $role->name . ' Applicant';
            $lastName  = 'Example';

            // DOB required for all
            $dob = fake()->dateTimeBetween('-90 years', '-18 years')->format('Y-m-d');

            $data = [
                'first_name'   => $firstName,
                'last_name'    => $lastName,
                'email'        => strtolower($role->name) . '.applicant@ohms.test',
                'phone'        => fake()->phoneNumber(),
                'address'      => fake()->address(),
                'role_id'      => $role->id,
                'password'     => Hash::make('password'),
                'status'       => 'pending',
                'approved_by'  => null,
                'approved_at'  => null,
                'date_of_birth'=> $dob,

                // Default null, only used for Patients
                'family_code'                => null,
                'emergency_contact'          => null,
                'emergency_contact_relation' => null,
            ];

            if ($role->name === 'Patient') {
                $data['family_code']                = 'FC-' . strtoupper(Str::random(6));
                $data['emergency_contact']          = fake()->name();
                $data['emergency_contact_relation'] = 'Parent';
            }

            UserApplication::create($data);
        }
    }
}
