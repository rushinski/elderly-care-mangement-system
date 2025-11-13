<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FamilyMember;
use App\Models\Patient;
use Illuminate\Support\Str;

class FamilyMemberSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();

        foreach ($patients as $patient) {
            FamilyMember::create([
                'patient_id' => $patient->id,
                'name' => fake()->name(),
                'relationship' => ['Son', 'Daughter', 'Sibling'][rand(0, 2)],
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'family_code' => strtoupper(Str::random(8)),
            ]);
        }
    }
}
