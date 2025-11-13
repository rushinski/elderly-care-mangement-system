<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Roster;
use App\Models\Supervisor;
use App\Models\Doctor;
use App\Models\Caregiver;

class RosterSeeder extends Seeder
{
    public function run(): void
    {
        $supervisors = Supervisor::all();
        $doctors = Doctor::all();
        $caregivers = Caregiver::all();

        foreach ($supervisors as $supervisor) {
            Roster::create([
                'supervisor_id' => $supervisor->id,
                'doctor_id' => $doctors->random()->id ?? null,
                'caregiver_id' => $caregivers->random()->id ?? null,
                'date' => now()->addDays(rand(0, 5)),
                'shift' => rand(0, 1) ? 'Day' : 'Night',
                'notes' => fake()->sentence(8),
            ]);
        }
    }
}
