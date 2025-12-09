<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyTask;
use App\Models\Roster;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DailyTaskSeeder extends Seeder
{
    public function run(): void
    {
        $rosters  = Roster::all();
        $patients = Patient::all();

        // These are the human-readable names that will show in any UI
        $tasks = [
            'Morning Medication',
            'Lunch',
            'Dinner',
            'Vitals Check',
            'Exercise Session',
        ];

        if ($rosters->isEmpty() || $patients->isEmpty()) {
            // Nothing to seed against – just bail cleanly
            return;
        }

        foreach ($rosters as $roster) {
            // Take up to 2 random patients for this roster
            $randomPatients = $patients->random(min(2, $patients->count()));

            foreach ($randomPatients as $patient) {
                foreach ($tasks as $taskName) {
                    DailyTask::create([
                        'roster_id'    => $roster->id,
                        'patient_id'   => $patient->id,

                        // NEW FIELDS (required by your migration)
                        'task_date'    => Carbon::today(),                // or random date if you prefer
                        'task_type'    => Str::slug($taskName, '_'),      // e.g. 'Morning Medication' -> 'morning_medication'

                        'task_name'    => $taskName,
                        'completed'    => (bool) rand(0, 1),
                        'completed_at' => rand(0, 1)
                            ? Carbon::now()->subHours(rand(1, 8))
                            : null,
                        'remarks'      => fake()->sentence(5),
                    ]);
                }
            }
        }
    }
}
