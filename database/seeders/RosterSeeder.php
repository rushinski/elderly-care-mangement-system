<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Roster;
use App\Models\Supervisor;
use App\Models\Doctor;
use App\Models\User;

class RosterSeeder extends Seeder
{
    public function run(): void
    {
        $date = now()->toDateString();

        $supervisor = Supervisor::with('user')->first();
        $doctor     = Doctor::with('user')->first();

        if (!$supervisor || !$supervisor->user || !$doctor || !$doctor->user) {
            return;
        }

        // Caregiver USERS (not Caregiver model IDs)
        $caregiverAUser = User::where('email', 'caregiverA@ohms.test')->first();
        $caregiverBUser = User::where('email', 'caregiverB@ohms.test')->first();
        $caregiverCUser = User::where('email', 'caregiverC@ohms.test')->first();
        $caregiverDUser = User::where('email', 'caregiverD@ohms.test')->first();

        if (
            !$caregiverAUser || !$caregiverBUser ||
            !$caregiverCUser || !$caregiverDUser
        ) {
            return;
        }

        Roster::updateOrCreate(
            ['date' => $date],
            [
                'supervisor_id'  => $supervisor->user_id,    // users.id
                'doctor_id'      => $doctor->user_id,        // users.id
                'caregiver_1_id' => $caregiverAUser->id,     // users.id
                'caregiver_2_id' => $caregiverBUser->id,
                'caregiver_3_id' => $caregiverCUser->id,
                'caregiver_4_id' => $caregiverDUser->id,
            ]
        );
    }
}
