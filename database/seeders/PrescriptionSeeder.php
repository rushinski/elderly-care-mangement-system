<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prescription;
use App\Models\Appointment;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::all();

        if ($appointments->isEmpty()) {
            return;
        }

        $meds = ['Paracetamol', 'Ibuprofen', 'Amoxicillin', 'Metformin'];

        foreach ($appointments as $appointment) {
            Prescription::create([
                'appointment_id' => $appointment->id,
                'patient_id'     => $appointment->patient_id,
                'comment'        => fake()->sentence(10),
                'morning_med'    => fake()->randomElement($meds),
                'afternoon_med'  => rand(0, 1) ? fake()->randomElement($meds) : null,
                'night_med'      => rand(0, 1) ? fake()->randomElement($meds) : null,
            ]);
        }
    }
}
