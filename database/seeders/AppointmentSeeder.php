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
        $doctors  = Doctor::all();
        $patients = Patient::all();

        if ($doctors->isEmpty() || $patients->isEmpty()) {
            return;
        }

        $statuses = ['Scheduled', 'Completed', 'Cancelled'];

        foreach ($patients as $patient) {
            Appointment::create([
                'doctor_id'        => $doctors->random()->id,              // doctors.id
                'patient_id'       => $patient->id,                         // patients.id
                'appointment_date' => now()->addDays(rand(-5, 10))->toDateString(),
                'status'           => $statuses[array_rand($statuses)],
                'notes'            => fake()->sentence(8),
            ]);
        }
    }
}
