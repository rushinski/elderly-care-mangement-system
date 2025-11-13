<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PatientSeeder::class,
            SupervisorSeeder::class,
            DoctorSeeder::class,
            CaregiverSeeder::class,
            FamilyMemberSeeder::class,
            RosterSeeder::class,
            AppointmentSeeder::class,
            PrescriptionSeeder::class,
            DailyTaskSeeder::class,
            PaymentSeeder::class,
            ReportSeeder::class,
        ]);
    }

}
