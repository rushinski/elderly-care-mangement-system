<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyTask;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Roster;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    /**
     * Shows missed activities for the selected date.
     * Visible to Admin & Supervisor.
     */
    public function missedActivity(Request $request)
    {
        $date = $request->query('date', Carbon::today()->toDateString());

        // All patients
        $patients = Patient::with(['user'])->get();

        $results = [];

        foreach ($patients as $patient) {

            // --------------------------
            // Doctor Appointment for the date
            // --------------------------
            $appointment = Appointment::with(['doctor.user'])
                ->where('patient_id', $patient->id)
                ->whereDate('appointment_date', $date)
                ->first();

            // --------------------------
            // Roster → Caregiver for that date
            // --------------------------
            $roster = Roster::with(['caregiver1', 'caregiver2', 'caregiver3', 'caregiver4'])
                ->whereDate('date', $date)
                ->first();

            $caregiverName = '-';

            if ($roster) {
                $cg = collect([
                    $roster->caregiver1,
                    $roster->caregiver2,
                    $roster->caregiver3,
                    $roster->caregiver4,
                ])->filter()->first();

                if ($cg && $cg->user) {
                    $caregiverName = "{$cg->user->first_name} {$cg->user->last_name}";
                }
            }

            // --------------------------
            // Daily Tasks for the patient/date
            // --------------------------
            $tasks = DailyTask::where('patient_id', $patient->id)
                ->whereDate('task_date', $date)
                ->get()
                ->keyBy('task_name');

            // Return:
            // - "Missing" if no task row exists
            // - "Yes"     if completed == 1
            // - "No"      if completed == 0
            $taskValue = function (string $key) use ($tasks) {
                if (!isset($tasks[$key])) {
                    return 'Missing';
                }

                return $tasks[$key]->completed ? 'Yes' : 'No';
            };


            // Build result row
            $results[] = [
                'patient_name'         => $patient->user?->full_name ?? '-',
                'doctor_name'          => optional($appointment?->doctor?->user)->full_name ?? '-',
                'doctor_appointment'   => $appointment ? 'Yes' : 'No',
                'caregiver_name'       => $caregiverName,

                'morning_medicine'     => $taskValue('morning_medicine'),
                'afternoon_medicine'   => $taskValue('afternoon_medicine'),
                'night_medicine'       => $taskValue('night_medicine'),
                'breakfast'            => $taskValue('breakfast'),
                'lunch'                => $taskValue('lunch'),
                'dinner'               => $taskValue('dinner'),
            ];
        }

        return view('admin.missed-activity', compact('results', 'date'));
    }
}
