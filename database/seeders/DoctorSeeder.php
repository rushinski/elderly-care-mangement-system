<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctorUsers = User::whereHas('role', function ($q) {
            $q->where('name', 'Doctor')
              ->orWhere('name', 'Nurse');
        })->get();

        foreach ($doctorUsers as $user) {
            Doctor::create([
                'user_id' => $user->id,
                'specialization' => ['Geriatrics', 'Physiotherapy', 'General Medicine'][rand(0,2)],
                'license_number' => 'LIC-' . strtoupper(Str::random(6)),
                'shift' => rand(0,1) ? 'Day' : 'Night',
            ]);
        }
    }
}
