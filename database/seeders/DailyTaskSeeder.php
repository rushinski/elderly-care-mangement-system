<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyTask;
use App\Models\Roster;
use App\Models\Patient;
use Carbon\Carbon;

class DailyTaskSeeder extends Seeder
{
    public function run(): void
    {
        $rosters = Roster::all();
        $patients = Patient::all();
        $tasks = ['Morning Medication', 'Lunch', 'Dinner', 'Vitals Check', 'Exercise Session'];

        foreach ($rosters as $roster) {
            foreach ($patients->random(min(2, $patients->count())) as $patient) {
                foreach ($tasks as $task) {
                    DailyTask::create([
                        'roster_id' => $roster->id,
                        'patient_id' => $patient->id,
                        'task_name' => $task,
                        'completed' => (bool)rand(0, 1),
                        'completed_at' => rand(0, 1) ? Carbon::now()->subHours(rand(1, 8)) : null,
                        'remarks' => fake()->sentence(5),
                    ]);
                }
            }
        }
    }
}
