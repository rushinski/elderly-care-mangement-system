<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserApplicationController;
use App\Http\Controllers\StaffPatientController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RosterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All routes for the Old Home Management System (OHMS).
| Grouped by role and protected by authentication + role-based middleware.
| This file finalizes the backend routing for Phase 2.1.
|
*/
// Shared public view (everyone)
Route::middleware(['auth'])->group(function () {
    Route::get('/rosters', [RosterController::class, 'index'])->name('rosters.index');
});

// Restricted (Admin + Supervisor)
Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {
    Route::get('/rosters/create', [RosterController::class, 'create'])->name('rosters.create');
    Route::post('/rosters', [RosterController::class, 'store'])->name('rosters.store');
});
// Employee management (Admin only)
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/employees', [App\Http\Controllers\AdminController::class, 'employees'])->name('admin.employees.index');
    Route::post('/admin/employees/update-salary', [App\Http\Controllers\AdminController::class, 'updateSalary'])->name('admin.employees.updateSalary');
});


// ========================
// Authentication Routes
// ========================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ========================
// Role-Based Dashboards
// ========================
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('/admin/users', AdminController::class)->except(['index']);
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/admin/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/admin/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/admin/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/admin/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/admin/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('/admin/payments/summary', [PaymentController::class, 'summary'])->name('payments.summary');
    // --- Role management routes ---
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});
Route::post('/admin/employees/update-salary', [AdminController::class, 'updateSalary'])
    ->name('admin.employees.updateSalary')
    ->middleware(['auth', 'role:Admin']);

// ========================
// Supervisor Routes
// ========================
Route::middleware(['auth', 'role:Supervisor'])->group(function () {
    // Dashboard & Rosters
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'index'])->name('supervisor.dashboard');
    Route::resource('/supervisor/rosters', SupervisorController::class)->except(['index']);

    // Shared Admin Pages (Read-only / Controlled Access)
    Route::get('/supervisor/employees', [SupervisorController::class, 'employees'])->name('supervisor.employees');
    Route::get('/supervisor/reports', [SupervisorController::class, 'reports'])->name('supervisor.reports');
    Route::get('/supervisor/appointments', [SupervisorController::class, 'appointments'])->name('supervisor.appointments');

    // Report Review Actions
    Route::post('/supervisor/reports/{report}/review', [SupervisorController::class, 'reviewReport'])
        ->name('supervisor.reviewReport');

    // Application Management (Shared Logic)
});

// ========================
// Doctor Routes
// ========================
Route::middleware(['auth', 'role:Doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'index'])->name('doctor.dashboard');
    Route::resource('/doctor/appointments', DoctorController::class)->except(['index']);
    Route::get('/doctor/prescriptions/create/{patient}', [DoctorController::class, 'createPrescription'])->name('doctor.createPrescription');
    Route::post('/doctor/prescriptions', [DoctorController::class, 'storePrescription'])->name('doctor.storePrescription');
});

// ========================
// Caregiver Routes
// ========================
Route::middleware(['auth', 'role:Caregiver'])->group(function () {
    // Caregiver home: shows date + list of assigned patients
    Route::get('/caregiver/dashboard', [CaregiverController::class, 'index'])
        ->name('caregiver.dashboard');

    // Patient chart for a specific patient on a given date
    Route::get('/caregiver/patient/{patient}', [CaregiverController::class, 'showPatient'])
        ->name('caregiver.patient.show');

    // Save checkbox updates for that patient + date
    Route::post('/caregiver/patient/{patient}/tasks', [CaregiverController::class, 'updatePatientTasks'])
        ->name('caregiver.patient.updateTasks');
});

// ========================
// Patient Routes
// ========================
Route::middleware(['auth', 'role:Patient'])->group(function () {
    Route::get('/patient/home', [PatientController::class, 'index'])->name('patient.dashboard');
    Route::get('/patient/appointment/{appointment}', [PatientController::class, 'showAppointment'])->name('patient.showAppointment');
    Route::get('/patient/prescription/{prescription}', [PatientController::class, 'showPrescription'])->name('patient.showPrescription');
    Route::get('/patient/payment/{payment}', [PatientController::class, 'showPayment'])->name('patient.showPayment');
});

// ========================
// Family Routes
// ========================
Route::middleware(['auth', 'role:Family'])->group(function () {
    Route::get('/family/home', [FamilyController::class, 'index'])->name('family.dashboard');
    Route::get('/family/appointment/{appointment}', [FamilyController::class, 'showAppointment'])->name('family.showAppointment');
    Route::get('/family/prescription/{prescription}', [FamilyController::class, 'showPrescription'])->name('family.showPrescription');
});

// ========================
// Default Fallback
// ========================
Route::fallback(function () {
    return redirect('/')->withErrors(['route' => 'The page you are looking for does not exist.']);
});

// =====================================
// Shared Patient Directory / Management
// =====================================

// All staff roles can view + search
Route::middleware(['auth', 'role:Admin,Supervisor,Doctor,Caregiver'])->group(function () {
    Route::get('/staff/patients', [StaffPatientController::class, 'index'])
        ->name('staff.patients.index');
});

// Only Admin + Supervisor can update group / admission_date
Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {
    Route::put('/staff/patients/{patient}', [StaffPatientController::class, 'update'])
        ->name('staff.patients.update');
});

// =========================
// Account Applications (Admin + Supervisor)
// =========================
Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {
    Route::get('/admin/applications', [UserApplicationController::class, 'index'])
        ->name('applications.index');

    Route::get('/admin/applications/{application}', [UserApplicationController::class, 'show'])
        ->name('applications.show');

    Route::post('/admin/applications/{application}/approve', [UserApplicationController::class, 'approve'])
        ->name('applications.approve');

    Route::post('/admin/applications/{application}/reject', [UserApplicationController::class, 'reject'])
        ->name('applications.reject');
});

