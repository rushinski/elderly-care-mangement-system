<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            User::create([
                'name' => $role->name . ' User',
                'email' => strtolower($role->name) . '@ohms.test',
                'password' => Hash::make('password'),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'role_id' => $role->id,
            ]);
        }
    }
}
