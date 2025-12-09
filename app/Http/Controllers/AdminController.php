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
     * Display the employee list for Supervisor (read-only).
     */
    public function indexSupervisor()
    {
        if (auth()->user()->role->name !== 'Supervisor') {
            abort(403, 'Unauthorized access.');
        }

        $employees = User::with('role')
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['Doctor', 'Nurse', 'Caregiver', 'Supervisor']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.employees.index', [
            'employees' => $employees,
            'readonly' => true,
        ]);
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

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show a specific user.
     */
    public function show(User $user)
    {
        return view('admin.show-user', compact('user'));
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
     * Update a user’s details.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Display all employees for Admin (editable salary).
     */
    public function employees()
    {
        $employees = User::with('role')
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['Doctor', 'Nurse', 'Caregiver', 'Supervisor']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $readonly = false; // Admin can edit salaries

        return view('admin.employees.index', compact('employees', 'readonly'));
    }

    /**
     * Update an employee’s salary (Admin only).
     */
    public function updateSalary(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'new_salary' => 'required|numeric|min:0',
        ]);

        $user = User::findOrFail($validated['employee_id']);
        $oldSalary = $user->salary ?? 0;

        $user->salary = $validated['new_salary'];
        $user->save();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', "Salary updated for {$user->first_name} {$user->last_name} (from \${$oldSalary} to \${$validated['new_salary']}).");
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'User deleted successfully.');
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
