<?php

namespace App\Http\Controllers;

use App\Models\Roster;
use App\Models\Patient;
use App\Models\DailyTask;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CaregiverController extends Controller
{
    /**
     * Caregiver dashboard:
     * - Shows date selector
     * - Determines caregiver's group (A/B/C/D) from roster
     * - Lists all patients in that group
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        // Date filter: default to today, changeable via ?date=
        $selectedDate = $request->query('date', now()->toDateString());

        // Find roster for that day
        $roster = Roster::whereDate('date', $selectedDate)->first();

        $group = null;
        if ($roster) {
            $group = $this->determineGroupForCaregiver($roster, $userId);
        }

        // If caregiver not on roster or no group, there will be no patients
        $patients = collect();
        if ($group) {
            $patients = Patient::with('user')
                ->where('group', $group)
                ->orderBy('id')
                ->get();
        }

        return view('caregiver.dashboard', [
            'selectedDate' => $selectedDate,
            'roster'       => $roster,
            'group'        => $group,
            'patients'     => $patients,
        ]);
    }

    /**
     * Show a specific patient's chart for this caregiver and date.
     * Caregiver can toggle the six standard tasks.
     */
    public function showPatient(Patient $patient, Request $request)
    {
        $userId = auth()->id();
        $selectedDate = $request->query('date', now()->toDateString());

        $roster = Roster::whereDate('date', $selectedDate)->firstOrFail();
        $group  = $this->determineGroupForCaregiver($roster, $userId);

        // Security: caregiver must be assigned to this patient group on that day
        if (!$group || $patient->group !== $group) {
            abort(403, 'This patient is not assigned to you for the selected day.');
        }

        $tasks = DailyTask::where('patient_id', $patient->id)
            ->whereDate('task_date', $selectedDate)
            ->get()
            ->keyBy('task_type');

        return view('caregiver.patient-tasks', [
            'patient'      => $patient,
            'selectedDate' => $selectedDate,
            'tasks'        => $tasks,
        ]);
    }

    /**
     * Save checkbox updates for a patient + date.
     */
    public function updatePatientTasks(Patient $patient, Request $request)
    {
        $userId       = auth()->id();
        $selectedDate = $request->input('date', now()->toDateString());

        $roster = Roster::whereDate('date', $selectedDate)->firstOrFail();
        $group  = $this->determineGroupForCaregiver($roster, $userId);

        if (!$group || $patient->group !== $group) {
            abort(403, 'You are not allowed to update this patient for the selected day.');
        }

        // The six standard tasks used on patient & caregiver screens
        $standardTasks = [
            DailyTask::TASK_MORNING_MEDICINE,
            DailyTask::TASK_AFTERNOON_MEDICINE,
            DailyTask::TASK_NIGHT_MEDICINE,
            DailyTask::TASK_BREAKFAST,
            DailyTask::TASK_LUNCH,
            DailyTask::TASK_DINNER,
        ];

        foreach ($standardTasks as $taskType) {
            $checked = $request->boolean($taskType);

            $task = DailyTask::firstOrNew([
                'patient_id' => $patient->id,
                'task_date'  => $selectedDate,
                'task_type'  => $taskType,
            ]);

            $task->roster_id = $roster->id;
            $task->task_name = $task->task_name ?: ucfirst(str_replace('_', ' ', $taskType));
            $task->completed = $checked;


            $task->save();
        }

        return redirect()
            ->route('caregiver.patient.show', [
                'patient' => $patient->id,
                'date'    => $selectedDate,
            ])
            ->with('success', 'Tasks updated successfully.');
    }

    /**
     * Map caregiver user ID to group letter (A/B/C/D) for a given roster row.
     */
    private function determineGroupForCaregiver(Roster $roster, int $caregiverUserId): ?string
    {
        if ((int) $roster->caregiver_1_id === $caregiverUserId) {
            return 'A';
        }
        if ((int) $roster->caregiver_2_id === $caregiverUserId) {
            return 'B';
        }
        if ((int) $roster->caregiver_3_id === $caregiverUserId) {
            return 'C';
        }
        if ((int) $roster->caregiver_4_id === $caregiverUserId) {
            return 'D';
        }

        return null;
    }


}
