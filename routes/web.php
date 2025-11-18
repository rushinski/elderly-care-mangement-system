<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\PaymentController;

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

// ========================
// 🔐 Authentication Routes
// ========================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ========================
// 🧭 Role-Based Dashboards
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
});

Route::middleware(['auth', 'role:Supervisor'])->group(function () {
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'index'])->name('supervisor.dashboard');
    Route::resource('/supervisor/rosters', SupervisorController::class)->except(['index']);
    Route::post('/supervisor/reports/{report}/review', [SupervisorController::class, 'reviewReport'])->name('supervisor.reviewReport');
});

Route::middleware(['auth', 'role:Doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'index'])->name('doctor.dashboard');
    Route::resource('/doctor/appointments', DoctorController::class)->except(['index']);
    Route::get('/doctor/prescriptions/create/{patient}', [DoctorController::class, 'createPrescription'])->name('doctor.createPrescription');
    Route::post('/doctor/prescriptions', [DoctorController::class, 'storePrescription'])->name('doctor.storePrescription');
});

Route::middleware(['auth', 'role:Caregiver'])->group(function () {
    Route::get('/caregiver/dashboard', [CaregiverController::class, 'index'])->name('caregiver.dashboard');
    Route::resource('/caregiver/tasks', CaregiverController::class)->except(['index']);
});

Route::middleware(['auth', 'role:Patient'])->group(function () {
    Route::get('/patient/home', [PatientController::class, 'index'])->name('patient.dashboard');
    Route::get('/patient/appointment/{appointment}', [PatientController::class, 'showAppointment'])->name('patient.showAppointment');
    Route::get('/patient/prescription/{prescription}', [PatientController::class, 'showPrescription'])->name('patient.showPrescription');
    Route::get('/patient/payment/{payment}', [PatientController::class, 'showPayment'])->name('patient.showPayment');
});

Route::middleware(['auth', 'role:Family'])->group(function () {
    Route::get('/family/home', [FamilyController::class, 'index'])->name('family.dashboard');
    Route::get('/family/appointment/{appointment}', [FamilyController::class, 'showAppointment'])->name('family.showAppointment');
    Route::get('/family/prescription/{prescription}', [FamilyController::class, 'showPrescription'])->name('family.showPrescription');
});

// ========================
// 🚀 Default Fallback
// ========================
Route::fallback(function () {
    return redirect('/')->withErrors(['route' => 'The page you are looking for does not exist.']);
});
