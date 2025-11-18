<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Report;
use App\Models\Payment;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with user, report, and payment summaries.
     */
    public function index()
    {
        $users = User::with('role')->get();
        $reports = Report::latest()->take(10)->get();
        $payments = Payment::latest()->take(10)->get();

        return view('admin.dashboard', compact('users', 'reports', 'payments'));
    }

    /**
     * Display form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.create-user', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->route('admin.index')->with('success', 'User created successfully.');
    }

    /**
     * Edit a specific user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.edit-user', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($validated);

        return redirect()->route('admin.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
    }

    /**
     * View system reports.
     */
    public function reports()
    {
        $reports = Report::latest()->paginate(20);
        return view('admin.reports', compact('reports'));
    }

    /**
     * View payment summaries.
     */
    public function payments()
    {
        $payments = Payment::with('patient')->latest()->paginate(20);
        return view('admin.payments', compact('payments'));
    }
}
