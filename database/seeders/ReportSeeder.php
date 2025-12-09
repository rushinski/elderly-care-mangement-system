<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\Patient;
use App\Models\User;
use App\Models\DailyTask;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $admins   = User::whereHas('role', fn ($r) => $r->where('name', 'Admin'))->get();
        $patients = Patient::all();

        if ($admins->isEmpty() || $patients->isEmpty()) {
            return;
        }

        foreach ($patients as $patient) {
            $tasks     = DailyTask::where('patient_id', $patient->id)->get();
            $total     = $tasks->count();
            $completed = $tasks->where('completed', true)->count();
            $missed    = $total - $completed;

            // simple status based on completion rate
            $status = match (true) {
                $total === 0          => 'no_tasks',
                $missed === 0         => 'on_track',
                $missed <= $total / 3 => 'minor_issues',
                default               => 'needs_attention',
            };

            Report::create([
                'patient_id'       => $patient->id,
                'admin_id'         => $admins->random()->id,
                'report_date'      => Carbon::now()->subDays(rand(0, 5)),
                'missed_tasks'     => $missed,
                'completed_tasks'  => $completed,
                'total_tasks'      => $total,
                'summary'          => "Out of {$total} tasks, {$completed} were completed and {$missed} missed.",
                'status'           => $status,
            ]);
        }
    }
}
