<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserApplication;
use App\Models\Role;

class AuthController extends Controller
{
    /**
     * Display login form.
     */
    public function showLogin()
    {
        Log::info('AuthController@showLogin → GET /login viewed');
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        Log::info('AuthController@login → POST /login attempt', [
            'email' => $request->input('email')
        ]);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = $user->role->name ?? 'Unknown';

            Log::info('✅ Auth success', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $role,
            ]);

            $redirectPath = match ($role) {
                'Admin'      => '/admin/dashboard',
                'Supervisor' => '/supervisor/dashboard',
                'Doctor'     => '/doctor/dashboard',
                'Caregiver'  => '/caregiver/dashboard',
                'Patient'    => '/patient/home',
                'Family'     => '/family/home',
                default      => '/',
            };

            Log::info('🔁 Redirecting user after login', [
                'email' => $user->email,
                'to' => $redirectPath
            ]);

            return redirect($redirectPath);
        }

        Log::warning('❌ Auth failed', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
        ]);

        return back()->withErrors([
            'email' => 'Invalid credentials or account not found.',
        ]);
    }

    /**
     * Display registration form.
     */
    public function showRegister()
    {
        Log::info('AuthController@showRegister → GET /register viewed');
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    /**
     * Handle registration request.
     */
    public function store(Request $request)
    {
        // Determine role first so we can add conditional rules
        $role = Role::find($request->input('role_id'));

        $rules = [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:user_applications,email|unique:users,email',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'password'     => 'required|string|min:6|confirmed',
            'role_id'      => 'required|exists:roles,id',
            // ✅ DOB now required for **all** users
            'date_of_birth'=> 'required|date',
        ];

        // Extra required fields if role is Patient
        if ($role && $role->name === 'Patient') {
            $rules['family_code']                = 'required|string|max:50';
            $rules['emergency_contact']          = 'required|string|max:255';
            $rules['emergency_contact_relation'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        UserApplication::create([
            'first_name'                 => $validated['first_name'],
            'last_name'                  => $validated['last_name'],
            'email'                      => $validated['email'],
            'phone'                      => $validated['phone'] ?? null,
            'address'                    => $validated['address'] ?? null,
            'role_id'                    => $validated['role_id'],
            'password'                   => Hash::make($validated['password']),
            'date_of_birth'              => $validated['date_of_birth'], // now always set
            'family_code'                => $validated['family_code'] ?? null,
            'emergency_contact'          => $validated['emergency_contact'] ?? null,
            'emergency_contact_relation' => $validated['emergency_contact_relation'] ?? null,
            'status'                     => 'pending',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Registration submitted. Your account is pending approval.');
    }


    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $email = $user ? $user->email : 'guest';
        Log::info('🚪 Logout initiated', ['email' => $email]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('✅ Logout complete', ['email' => $email]);
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
