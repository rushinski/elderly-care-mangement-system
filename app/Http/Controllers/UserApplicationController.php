<?php
// app/Http/Controllers/UserApplicationController.php

namespace App\Http\Controllers;

use App\Models\UserApplication;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserApplicationController extends Controller
{
    public function index()
    {
        $applications = UserApplication::with('role')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('applications.index', compact('applications'));
    }

    public function show(UserApplication $application)
    {
        return view('applications.show', compact('application'));
    }

    public function approve(UserApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->withErrors('Application already processed.');
        }

        DB::transaction(function () use ($application) {
            // Create actual User
            $user = User::create([
                'first_name' => $application->first_name,
                'last_name'  => $application->last_name,
                'email'      => $application->email,
                'password'   => $application->password,
                'phone'      => $application->phone,
                'address'    => $application->address,
                'role_id'    => $application->role_id,
                'date_of_birth' => $application->date_of_birth,
            ]);

            // If this is a Patient application, create Patient record
            if ($application->isPatient()) {
                Patient::create([
                    'user_id'                    => $user->id,
                    // Keep using family_code as patient_code if that's your current design
                    'patient_code'               => $application->family_code,
                    'family_code'                => $application->family_code,
                    'emergency_contact'          => $application->emergency_contact,
                    'emergency_contact_relation' => $application->emergency_contact_relation,
                    'group'                      => null,
                    'admission_date'             => now(),
                    'medical_history'            => null,
                ]);
            }

            $application->update([
                'status'      => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        });

        return redirect()
            ->route('applications.index')
            ->with('success', 'Application approved and user created.');
    }


    public function reject(Request $request, UserApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->withErrors('Application already processed.');
        }

        $application->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('applications.index')
            ->with('success', 'Application rejected.');
    }
}
