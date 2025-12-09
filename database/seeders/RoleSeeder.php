<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin',      'access_level' => 0],
            ['name' => 'Supervisor', 'access_level' => 1],
            ['name' => 'Doctor',     'access_level' => 2],
            ['name' => 'Caregiver',  'access_level' => 3],
            ['name' => 'Patient',    'access_level' => 4],
            ['name' => 'Family',     'access_level' => 5],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['access_level' => $role['access_level']]
            );
        }
    }
}
