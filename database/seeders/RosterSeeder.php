<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Roster;
use App\Models\Supervisor;
use App\Models\Doctor;
use App\Models\Caregiver;
use App\Models\User;

class RosterSeeder extends Seeder
{
    public function run(): void
    {
        $date = now()->toDateString();

        // --- Supervisor & Doctor come from their OWN tables (FK targets) ---
        $supervisor = Supervisor::first();
        $doctor     = Doctor::first();

        if (!$supervisor || !$doctor) {
            // If these aren't seeded yet, don't blow up
            return;
        }

        // --- Map our four test caregiver USERS to their Caregiver rows ---

        $caregiverAUser = User::where('email', 'caregiverA@ohms.test')->first();
        $caregiverBUser = User::where('email', 'caregiverB@ohms.test')->first();
        $caregiverCUser = User::where('email', 'caregiverC@ohms.test')->first();
        $caregiverDUser = User::where('email', 'caregiverD@ohms.test')->first();

        if (!$caregiverAUser || !$caregiverBUser || !$caregiverCUser || !$caregiverDUser) {
            return;
        }

        $caregiverA = Caregiver::where('user_id', $caregiverAUser->id)->first();
        $caregiverB = Caregiver::where('user_id', $caregiverBUser->id)->first();
        $caregiverC = Caregiver::where('user_id', $caregiverCUser->id)->first();
        $caregiverD = Caregiver::where('user_id', $caregiverDUser->id)->first();

        if (!$caregiverA || !$caregiverB || !$caregiverC || !$caregiverD) {
            return;
        }

        // --- Create/update roster for TODAY ---
        Roster::updateOrCreate(
            ['date' => $date],
            [
                'supervisor_id' => $supervisor->id,   // FK -> supervisors.id
                'doctor_id'     => $doctor->id,       // FK -> doctors.id
                'caregiver_1'   => $caregiverA->id,   // Group A
                'caregiver_2'   => $caregiverB->id,   // Group B
                'caregiver_3'   => $caregiverC->id,   // Group C
                'caregiver_4'   => $caregiverD->id,   // Group D
            ]
        );
    }
}
