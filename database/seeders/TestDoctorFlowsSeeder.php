<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;

class TestDoctorFlowsSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------
        // 1) Roles
        // ---------------------------------
        $doctorRole  = Role::where('name', 'Doctor')->firstOrFail();
        $patientRole = Role::where('name', 'Patient')->firstOrFail();

        // ---------------------------------
        // 2) Doctor (user + doctor row)
        // ---------------------------------
        $doctorUser = User::firstOrCreate(
            ['email' => 'test-doctor@ohms.test'],
            [
                'first_name' => 'Test',
                'last_name'  => 'Doctor',
                'password'   => Hash::make('password'),
                'role_id'    => $doctorRole->id,
            ]
        );

        // Doctor table now only: id, user_id, specialization
        $doctor = Doctor::firstOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'specialization' => 'General Medicine',
            ]
        );

        // ---------------------------------
        // 3) Create 3 Patients
        // ---------------------------------
        $patients = [];

        for ($i = 1; $i <= 3; $i++) {
            $user = User::firstOrCreate(
                ['email' => "test-patient{$i}@ohms.test"],
                [
                    'first_name' => "TestPatient{$i}",
                    'last_name'  => 'Example',
                    'password'   => Hash::make('password'),
                    'role_id'    => $patientRole->id,
                ]
            );

            // patient_code is REQUIRED, so we generate it
            $patients[$i] = Patient::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'patient_code'               => "PATCODE{$i}",
                    'family_code'                => "FAMCODE{$i}",
                    'emergency_contact'          => 'Test Emergency',
                    'emergency_contact_relation' => 'Sibling',
                    'group'                      => 'A',
                    'admission_date'             => Carbon::today()->subDays(30),
                ]
            );
        }

        $patient1 = $patients[1];
        $patient2 = $patients[2];
        $patient3 = $patients[3];

        // ---------------------------------
        // 4) Appointments (NO appointment_time)
        // ---------------------------------
        $today = Carbon::today();

        // Past appointment
        $pastAppointment = Appointment::create([
            'doctor_id'        => $doctor->id,
            'patient_id'       => $patient1->id,
            'appointment_date' => $today->copy()->subDays(2)->toDateString(),
            'status'           => 'Completed',
            'notes'            => 'Follow-up on previous condition.',
        ]);

        // Today appointment (used for Pg. 15: can prescribe only if today)
        $todayAppointment = Appointment::create([
            'doctor_id'        => $doctor->id,
            'patient_id'       => $patient1->id,
            'appointment_date' => $today->toDateString(),
            'status'           => 'Scheduled',
            'notes'            => 'Routine check-up.',
        ]);

        // Future appointment
        $futureAppointment = Appointment::create([
            'doctor_id'        => $doctor->id,
            'patient_id'       => $patient2->id,
            'appointment_date' => $today->copy()->addDays(3)->toDateString(),
            'status'           => 'Scheduled',
            'notes'            => 'New consultation.',
        ]);

        // ---------------------------------
        // 5) Prescriptions
        // ---------------------------------

        // Old prescription (past appointment)
        Prescription::create([
            'appointment_id' => $pastAppointment->id,
            'patient_id'     => $patient1->id,
            'comment'        => 'Continue current medication.',
            'morning_med'    => 'Yes',
            'afternoon_med'  => null,
            'night_med'      => 'Yes',
        ]);

        // Today's prescription (most recent)
        Prescription::create([
            'appointment_id' => $todayAppointment->id,
            'patient_id'     => $patient1->id,
            'comment'        => 'Adjust dosage based on results.',
            'morning_med'    => 'Yes',
            'afternoon_med'  => 'Yes',
            'night_med'      => 'Yes',
        ]);

        // Patient2 — optional test prescription
        Prescription::create([
            'appointment_id' => $futureAppointment->id,
            'patient_id'     => $patient2->id,
            'comment'        => 'Prepare meds for upcoming visit.',
            'morning_med'    => null,
            'afternoon_med'  => 'Yes',
            'night_med'      => null,
        ]);

        $this->command->info('TestDoctorFlowsSeeder: seeded doctor, 3 patients, appointments, prescriptions.');
    }
}
