<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        foreach ($patients as $patient) {
            Appointment::create([
                'doctor_id' => $doctors->random()->id,
                'patient_id' => $patient->id,
                'appointment_date' => now()->addDays(rand(1, 10)),
                'appointment_time' => now()->addHours(rand(8, 17))->format('H:i:s'),
                'status' => ['Scheduled', 'Completed', 'Cancelled'][rand(0, 2)],
                'notes' => fake()->sentence(8),
            ]);
        }
    }
}
