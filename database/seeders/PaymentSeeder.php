<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Patient;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();

        if ($patients->isEmpty()) {
            return;
        }

        foreach ($patients as $patient) {
            $days         = rand(10, 30);
            $appointments = rand(1, 3);

            $dailyRate       = rand(80, 150);
            $appointmentRate = rand(20, 60);
            $medicineRate    = rand(10, 50);

            Payment::create([
                'patient_id'       => $patient->id,
                'daily_rate'       => $dailyRate,
                'appointment_rate' => $appointmentRate,
                'medicine_rate'    => $medicineRate,
                'days'             => $days,
                'appointments'     => $appointments,
                'status'           => rand(0, 1) ? 'Paid' : 'Unpaid',
                'payment_date'     => rand(0, 1)
                    ? Carbon::now()->subDays(rand(1, 10))
                    : null,
                // total_amount is auto-calculated in the model
            ]);
        }
    }
}
