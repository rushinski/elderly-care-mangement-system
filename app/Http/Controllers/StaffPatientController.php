<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffPatientController extends Controller
{
    /**
     * Unified patient management + directory page.
     *
     * - Top section:
     *   * Search by patient ID / code.
     *   * If Admin or Supervisor, can edit group + admission_date.
     *
     * - Bottom section:
     *   * Read-only list of patients with search over ID, name,
     *     age, emergency contact, emergency contact relation,
     *     admission date.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->role->name ?? null;

        $canEdit = in_array($roleName, ['Admin', 'Supervisor'], true);

        // ---------- Top: search by ID (or patient_code) ----------
        $patientIdSearch = trim((string) $request->input('patient_id'));

        $selectedPatient = null;

        if ($patientIdSearch !== '') {
            $selectedPatient = Patient::with('user')
                ->where('patient_code', $patientIdSearch)
                ->orWhere('id', $patientIdSearch) // fallback if you use numeric IDs
                ->first();
        }

        // ---------- Bottom: directory search ----------
        $directorySearch = trim((string) $request->input('search'));

        $query = Patient::with('user');

        if ($directorySearch !== '') {
            $query->where(function ($q) use ($directorySearch) {
                $q->where('patient_code', 'like', "%{$directorySearch}%")
                    ->orWhere('emergency_contact', 'like', "%{$directorySearch}%")
                    ->orWhere('emergency_contact_relation', 'like', "%{$directorySearch}%")
                    ->orWhere('group', 'like', "%{$directorySearch}%")
                    ->orWhere('admission_date', 'like', "%{$directorySearch}%")
                    ->orWhereHas('user', function ($userQuery) use ($directorySearch) {
                        $userQuery->where('first_name', 'like', "%{$directorySearch}%")
                            ->orWhere('last_name', 'like', "%{$directorySearch}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$directorySearch}%"])
                            ->orWhere('email', 'like', "%{$directorySearch}%");
                    });
            });
        }

        $patients = $query
            ->orderBy('id', 'asc')
            ->paginate(25)
            ->withQueryString();

        return view('staff.patients.index', [
            'selectedPatient'   => $selectedPatient,
            'patientIdSearch'   => $patientIdSearch,
            'patients'          => $patients,
            'directorySearch'   => $directorySearch,
            'canEdit'           => $canEdit,
        ]);
    }

    /**
     * Update group + admission_date for a patient.
     * Restricted to Admin + Supervisor.
     */
    public function update(Request $request, Patient $patient)
    {
        $user = Auth::user();
        $roleName = $user->role->name ?? null;

        if (!in_array($roleName, ['Admin', 'Supervisor'], true)) {
            abort(403, 'You are not allowed to update patient records.');
        }

        $validated = $request->validate([
            'group'          => 'nullable|string|max:255',
            'admission_date' => 'required|date',
        ]);

        $patient->update($validated);

        return redirect()
            ->route('staff.patients.index', ['patient_id' => $patient->patient_code ?? $patient->id])
            ->with('success', 'Patient information updated successfully.');
    }
}
