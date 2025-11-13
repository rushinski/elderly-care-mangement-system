<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caregiver;
use App\Models\User;

class CaregiverSeeder extends Seeder
{
    public function run(): void
    {
        $caregiverUsers = User::whereHas('role', function ($q) {
            $q->where('name', 'Caregiver');
        })->get();

        foreach ($caregiverUsers as $user) {
            Caregiver::create([
                'user_id' => $user->id,
                'shift' => rand(0,1) ? 'Day' : 'Night',
                'assigned_patients' => rand(2,5),
                'performance_rating' => ['Excellent', 'Good', 'Satisfactory'][rand(0,2)],
            ]);
        }
    }
}
