<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'description' => 'System administrator'],
            ['name' => 'Supervisor', 'description' => 'Supervises caregivers and staff'],
            ['name' => 'Doctor', 'description' => 'Manages appointments and prescriptions'],
            ['name' => 'Caregiver', 'description' => 'Handles daily patient tasks'],
            ['name' => 'Patient', 'description' => 'Receives care and schedules'],
            ['name' => 'Family', 'description' => 'Read-only patient viewer'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
