<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        // Find any users with the "Patient" role
        $patientUsers = User::whereHas('role', function ($q) {
            $q->where('name', 'Patient');
        })->get();

        foreach ($patientUsers as $user) {
            Patient::create([
                'user_id' => $user->id,
                'patient_code' => 'PT-' . strtoupper(Str::random(6)),
                'group' => 'A1',
                'admission_date' => now()->subDays(rand(1, 30)),
                'medical_history' => 'General observation, stable condition.',
            ]);
        }
    }
}
