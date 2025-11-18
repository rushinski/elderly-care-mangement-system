# 🧭 OHMS Controller–Route–Middleware Map
### Phase: 2.1 – Controller + Routing Layer
**System:** Old Home Management System (OHMS)  
**Framework:** Laravel 10 (PHP 8.1)  
**Branch:** `alex-dev`  
**Environment:** Laragon v6 (Apache + MySQL 8)

---

## 🏗️ Overview
This document outlines the **controller structure**, **route definitions**, and **middleware linkages** implemented in Phase 2.1 of the OHMS backend.

Each role in the system has a dedicated controller managing its functional scope, secured via `auth` and `role` middleware layers.

---

## 🧩 Middleware Stack

| Middleware | Path | Purpose |
|-----------|------|----------|
| `auth` | Laravel Core | Authentication (via Laravel Breeze) |
| `role` | `App\Http\Middleware\RoleMiddleware` | Restricts access by role |
| `verified` | Laravel Core | Optional email verification |
| `csrf` | Laravel Core | Protects all form POSTs from cross-site attacks |

**Registration:**
```php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

---

# 🧱 Controllers Overview

| # | Controller | Path | Models Used | Primary Role | Responsibilities |
|---|------------|------|-------------|--------------|------------------|
| 1 | AuthController | app/Http/Controllers/AuthController.php | User, Role | All | Handles login, logout, registration, and redirects users by role |
| 2 | AdminController | app/Http/Controllers/AdminController.php | User, Role, Report, Payment | Admin | Manages users, generates reports, oversees payment data |
| 3 | SupervisorController | app/Http/Controllers/SupervisorController.php | Roster, User, Report | Supervisor | Manages rosters, approves/rejects staff reports |
| 4 | DoctorController | app/Http/Controllers/DoctorController.php | Appointment, Prescription, Patient | Doctor | Handles appointments, prescriptions, patient management |
| 5 | CaregiverController | app/Http/Controllers/CaregiverController.php | Roster, DailyTask, Patient | Caregiver | Manages daily tasks and assigned patients |
| 6 | PatientController | app/Http/Controllers/PatientController.php | Patient, Appointment, Prescription, Payment | Patient | Displays dashboard: appointments, prescriptions, payments |
| 7 | FamilyController | app/Http/Controllers/FamilyController.php | Patient, FamilyMember, Appointment, Prescription | Family | Read-only access to linked patient records |
| 8 | PaymentController | app/Http/Controllers/PaymentController.php | Payment, Patient | Admin | CRUD + summary calculations |

---

# 🌐 Route Group Map

## 🔐 Authentication

| Route | Method | Controller@Action | Middleware |
|-------|--------|-------------------|------------|
| / | GET | AuthController@showLogin | guest |
| /login | POST | AuthController@login | guest |
| /register | GET | AuthController@showRegister | guest |
| /register | POST | AuthController@store | guest |
| /logout | GET | AuthController@logout | auth |

---

## 👑 Admin

| Route | Method | Controller@Action | Middleware |
|-------|--------|-------------------|------------|
| /admin/dashboard | GET | AdminController@index | auth, role:Admin |
| /admin/users | CRUD | AdminController | auth, role:Admin |
| /admin/reports | GET | AdminController@reports | auth, role:Admin |
| /admin/payments | CRUD | PaymentController | auth, role:Admin |
| /admin/payments/summary | GET | PaymentController@summary | auth, role:Admin |

---

## 🧭 Supervisor

| Route | Method | Controller@Action | Middleware |
|-------|--------|-------------------|------------|
| /supervisor/dashboard | GET | SupervisorController@index | auth, role:Supervisor |
| /supervisor/rosters | CRUD | SupervisorController | auth, role:Supervisor |
| /supervisor/reports/{report}/review | POST | SupervisorController@reviewReport | auth, role:Supervisor |

---

## ⚕️ Doctor

| Route | Method | Controller@Action | Middleware |
|-------|--------|-------------------|------------|
| /doctor/dashboard | GET | DoctorController@index | auth, role:Doctor |
| /doctor/appointments | CRUD | DoctorController | auth, role:Doctor |
| /doctor/prescriptions/create/{patient} | GET | DoctorController@createPrescription | auth, role:Doctor |
| /doctor/prescriptions | POST | DoctorController@storePrescription | auth, role:Doctor |

---

## 🩺 Caregiver

| Route | Method | Controller@Action | Middleware |
|-------|--------|-------------------|------------|
| /caregiver/dashboard | GET | CaregiverController@index | auth, role:Caregiver |
| /caregiver/tasks | CRUD | CaregiverController | auth, role:Caregiver |

---

## 🧍 Patient

| Route | Method | Controller@Action | Middleware |
|-------|--------|-------------------|------------|
| /patient/home | GET | PatientController@index | auth, role:Patient |
| /patient/appointment/{appointment} | GET | PatientController@showAppointment | auth, role:Patient |
| /patient/prescription/{prescription} | GET | PatientController@showPrescription | auth, role:Patient |
| /patient/payment/{payment} | GET | PatientController@showPayment | auth, role:Patient |

---

## 👨‍👩‍👧 Family

| Route | Method | Controller@Action | Middleware |
|-------|--------|-------------------|------------|
| /family/home | GET | FamilyController@index | auth, role:Family |
| /family/appointment/{appointment} | GET | FamilyController@showAppointment | auth, role:Family |
| /family/prescription/{prescription} | GET | FamilyController@showPrescription | auth, role:Family |

---

# 🔒 Security Layer Summary

| Area | Implementation |
|------|----------------|
| Authentication | Laravel Breeze |
| Authorization | RoleMiddleware |
| CSRF Protection | Laravel Default |
| Session Handling | Laravel Default |
| Access Filtering | Role-based route grouping |
| View Isolation | `resources/views/{role}/` |

---

# 🧮 Data Flow

1. **Request Entry:** User triggers action.  
2. **Middleware:**  
   - `auth` checks login  
   - `role` verifies correct role  
3. **Controller Logic:** Eloquent ORM operations  
4. **Response:** Blade view with compacted variables  

---

# 🧾 Validation Patterns

```php
$request->validate([
  'name' => 'required|string|max:255',
  'email' => 'required|email|unique:users,email',
  'role_id' => 'required|exists:roles,id',
]);
```

---

# 🧠 Notes for Frontend Developer

- Controllers return compacted data for views.
- All role-specific views under `/resources/views/{role}/`.
- Flash messages used for operation feedback.
- `@auth` and `@role` Blade directives available.

---

# ✅ Summary

The backend now provides:

- Secure role-based routing  
- Complete CRUD workflows  
- Modular architecture for future expansion  

The system is ready for frontend integration and ongoing development.

