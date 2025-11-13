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

        foreach ($appointments as $appointment) {
            Prescription::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'medicine_name' => ['Paracetamol', 'Ibuprofen', 'Amoxicillin'][rand(0, 2)],
                'dosage' => ['500mg', '250mg', '100mg'][rand(0, 2)],
                'frequency' => ['Once a day', 'Twice a day'][rand(0, 1)],
                'duration_days' => rand(3, 10),
                'instructions' => fake()->sentence(6),
            ]);
        }
    }
}
