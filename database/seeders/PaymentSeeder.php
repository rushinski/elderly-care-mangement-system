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

        foreach ($patients as $patient) {
            $days = rand(10, 30);
            $appointments = rand(1, 3);

            Payment::create([
                'patient_id' => $patient->id,
                'days' => $days,
                'appointments' => $appointments,
                'status' => rand(0, 1) ? 'Paid' : 'Unpaid',
                'payment_date' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 10)) : null,
            ]);
        }
    }
}
