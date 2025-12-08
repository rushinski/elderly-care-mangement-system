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
        $assignedPatients = Roster::where('user_id', $userId)
            ->with('patient')
            ->get();

        $tasks = DailyTask::where('caregiver_id', $userId)
            ->latest()
            ->get();

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

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Daily task created successfully.');
    }

    /**
     * Display a specific daily task (tasks.show).
     */
    public function show(DailyTask $task)
    {
        $this->authorizeTask($task);

        return view('caregiver.view-task', compact('task'));
    }

    /**
     * Edit a daily task.
     */
    public function edit(DailyTask $task)
    {
        $this->authorizeTask($task);
        $patients = Patient::all();

        return view('caregiver.edit-task', compact('task', 'patients'));
    }

    /**
     * Update an existing daily task.
     */
    public function update(Request $request, DailyTask $task)
    {
        $this->authorizeTask($task);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'description' => 'required|string|max:500',
            'status' => 'required|string|in:pending,completed',
        ]);

        $task->update($validated);

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Daily task updated successfully.');
    }

    /**
     * Delete a daily task.
     */
    public function destroy(DailyTask $task)
    {
        $this->authorizeTask($task);

        $task->delete();

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Verify task ownership for security.
     */
    private function authorizeTask(DailyTask $task)
    {
        if ($task->caregiver_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
