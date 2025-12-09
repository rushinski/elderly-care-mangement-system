<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roster;
use App\Models\User;

class RosterController extends Controller
{
    /**
     * Display all roster entries (accessible by all roles).
     */
    public function index()
    {
        $rosters = Roster::with(['supervisor', 'doctor', 'caregiver1', 'caregiver2', 'caregiver3', 'caregiver4'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('rosters.index', compact('rosters'));
    }

    /**
     * Display the create roster form (Admin & Supervisor only).
     */
    public function create()
    {
        $supervisors = User::whereHas('role', fn($q) => $q->where('name', 'Supervisor'))->get();
        $doctors = User::whereHas('role', fn($q) => $q->where('name', 'Doctor'))->get();
        $caregivers = User::whereHas('role', fn($q) => $q->where('name', 'Caregiver'))->get();

        return view('rosters.create', compact('supervisors', 'doctors', 'caregivers'));
    }

    /**
     * Store a new roster entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'          => 'required|date',
            'supervisor_id' => 'required|exists:users,id',
            'doctor_id'     => 'required|exists:users,id',
            'caregiver_1'   => 'nullable|exists:users,id',
            'caregiver_2'   => 'nullable|exists:users,id',
            'caregiver_3'   => 'nullable|exists:users,id',
            'caregiver_4'   => 'nullable|exists:users,id',
        ]);

        Roster::create($validated);

        return redirect()->route('rosters.index')->with('success', 'Roster created successfully.');
    }

    public function edit(Roster $roster)
    {
        $supervisors = User::whereHas('role', fn($q) => $q->where('name', 'Supervisor'))->get();
        $doctors     = User::whereHas('role', fn($q) => $q->where('name', 'Doctor'))->get();
        $caregivers  = User::whereHas('role', fn($q) => $q->where('name', 'Caregiver'))->get();

        return view('rosters.edit', compact('roster', 'supervisors', 'doctors', 'caregivers'));
    }

    public function update(Request $request, Roster $roster)
    {
        $validated = $request->validate([
            'date'          => 'required|date',
            'supervisor_id' => 'required|exists:users,id',
            'doctor_id'     => 'required|exists:users,id',
            'caregiver_1'   => 'nullable|exists:users,id',
            'caregiver_2'   => 'nullable|exists:users,id',
            'caregiver_3'   => 'nullable|exists:users,id',
            'caregiver_4'   => 'nullable|exists:users,id',
        ]);

        $roster->update($validated);

        return redirect()->route('rosters.index')
            ->with('success', 'Roster updated successfully.');
    }

    public function destroy(Roster $roster)
    {
        $roster->delete();

        return redirect()->route('rosters.index')
            ->with('success', 'Roster deleted successfully.');
    }
}
