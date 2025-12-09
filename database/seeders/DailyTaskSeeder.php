<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyTask;
use App\Models\Roster;
use App\Models\Patient;

class DailyTaskSeeder extends Seeder
{
    public function run(): void
    {
        $date     = now()->toDateString();
        $roster   = Roster::whereDate('date', $date)->first();
        $patients = Patient::all();

        if (!$roster || $patients->isEmpty()) {
            return;
        }

        $taskMap = [
            DailyTask::TASK_MORNING_MEDICINE   => 'Morning Medicine',
            DailyTask::TASK_AFTERNOON_MEDICINE => 'Afternoon Medicine',
            DailyTask::TASK_NIGHT_MEDICINE     => 'Night Medicine',
            DailyTask::TASK_BREAKFAST          => 'Breakfast',
            DailyTask::TASK_LUNCH              => 'Lunch',
            DailyTask::TASK_DINNER             => 'Dinner',
        ];

        foreach ($patients as $patient) {
            foreach ($taskMap as $type => $label) {
                DailyTask::updateOrCreate(
                    [
                        'roster_id'  => $roster->id,
                        'patient_id' => $patient->id,
                        'task_date'  => $date,
                        'task_type'  => $type,
                    ],
                    [
                        'task_name' => $label,
                        'completed' => (bool) rand(0, 1),
                    ]
                );
            }
        }
    }
}
