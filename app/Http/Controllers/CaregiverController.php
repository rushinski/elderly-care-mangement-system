<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roster;
use App\Models\DailyTask;
use App\Models\Patient;

class CaregiverController extends Controller
{
    /**
     * Display caregiver dashboard with assigned patients and daily tasks.
     */
    public function index()
    {
        $userId = auth()->id();
        $assignedPatients = Roster::where('user_id', $userId)->with('patient')->get();
        $tasks = DailyTask::where('caregiver_id', $userId)->latest()->get();

        return view('caregiver.dashboard', compact('assignedPatients', 'tasks'));
    }

    /**
     * Show form for creating a new daily task.
     */
    public function create()
    {
        $patients = Patient::all();
        return view('caregiver.create-task', compact('patients'));
    }

    /**
     * Store a new daily task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'description' => 'required|string|max:500',
            'status' => 'nullable|string|in:pending,completed',
        ]);

        DailyTask::create([
            'caregiver_id' => auth()->id(),
            'patient_id' => $validated['patient_id'],
            'description' => $validated['description'],
            'status' => $validated['status'] ?? 'pending',
        ]);

        return redirect()->route('caregiver.index')->with('success', 'Daily task created successfully.');
    }

    /**
     * Edit a daily task.
     */
    public function edit(DailyTask $dailyTask)
    {
        $this->authorizeTask($dailyTask);
        $patients = Patient::all();

        return view('caregiver.edit-task', compact('dailyTask', 'patients'));
    }

    /**
     * Update an existing daily task.
     */
    public function update(Request $request, DailyTask $dailyTask)
    {
        $this->authorizeTask($dailyTask);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'description' => 'required|string|max:500',
            'status' => 'required|string|in:pending,completed',
        ]);

        $dailyTask->update($validated);

        return redirect()->route('caregiver.index')->with('success', 'Daily task updated successfully.');
    }

    /**
     * Delete a daily task.
     */
    public function destroy(DailyTask $dailyTask)
    {
        $this->authorizeTask($dailyTask);

        $dailyTask->delete();
        return redirect()->route('caregiver.index')->with('success', 'Task deleted successfully.');
    }

    /**
     * Verify task ownership for security.
     */
    private function authorizeTask(DailyTask $dailyTask)
    {
        if ($dailyTask->caregiver_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
