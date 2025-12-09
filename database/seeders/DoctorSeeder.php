<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctorUsers = User::whereHas('role', function ($q) {
            $q->where('name', 'Doctor');
        })->get();

        foreach ($doctorUsers as $user) {
            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [] // only user_id is stored on Doctor model now
            );
        }
    }
}
