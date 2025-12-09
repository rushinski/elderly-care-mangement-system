<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffPatientController extends Controller
{
    /**
     * Unified patient management + directory page.
     *
     * - Top section (Admin + Supervisor only):
     *   * Search by patient name.
     *   * Show ID, Group, Admission Date.
     *   * Group + Admission Date editable.
     *
     * - Bottom section (Admin, Supervisor, Doctor, Caregiver):
     *   * Patient directory with search-by-field:
     *     ID, Name, Age, Emergency Contact, Emergency Contact Name, Admission Date.
     *   * Read-only.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->role->name ?? null;

        $canEdit = in_array($roleName, ['Admin', 'Supervisor'], true);

        // ==================================
        // TOP: "Additional Info" search
        // Only for Admin + Supervisor
        // ==================================
        $infoNameSearch = null;
        $selectedPatient = null;

        if ($canEdit) {
            $infoNameSearch = trim((string) $request->input('info_name'));

            if ($infoNameSearch !== '') {
                $selectedPatient = Patient::with('user')
                    ->whereHas('user', function ($q) use ($infoNameSearch) {
                        $q->where('first_name', 'like', "%{$infoNameSearch}%")
                            ->orWhere('last_name', 'like', "%{$infoNameSearch}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$infoNameSearch}%"]);
                    })
                    ->first();
            }
        }

        // ==================================
        // BOTTOM: Directory search
        // ==================================
        $directorySearch = trim((string) $request->input('search'));
        $searchField = $request->input('search_field', '');

        $query = Patient::with('user');

        if ($directorySearch !== '' && $searchField !== '') {
            switch ($searchField) {
                case 'id':
                    // ID = patients.id (numeric)
                    if (is_numeric($directorySearch)) {
                        $query->where('id', (int) $directorySearch);
                    } else {
                        $query->where('id', 'like', "%{$directorySearch}%");
                    }
                    break;

                case 'name':
                    // Name = user's first/last/full
                    $query->whereHas('user', function ($userQuery) use ($directorySearch) {
                        $userQuery->where('first_name', 'like', "%{$directorySearch}%")
                            ->orWhere('last_name', 'like', "%{$directorySearch}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$directorySearch}%"]);
                    });
                    break;

                case 'age':
                    // Age is derived from date_of_birth
                    if (is_numeric($directorySearch)) {
                        $age = (int) $directorySearch;

                        // People who are exactly $age years old
                        $to = Carbon::now()->subYears($age)->endOfDay();
                        $from = Carbon::now()->subYears($age + 1)->addDay()->startOfDay();

                        $query->whereHas('user', function ($userQuery) use ($from, $to) {
                            $userQuery->whereBetween('date_of_birth', [$from, $to]);
                        });
                    }
                    break;

                case 'emergency_contact':
                    $query->where('emergency_contact', 'like', "%{$directorySearch}%");
                    break;

                case 'emergency_contact_name':
                    // Mapped to emergency_contact_relation field
                    $query->where('emergency_contact_relation', 'like', "%{$directorySearch}%");
                    break;

                case 'admission_date':
                    $query->where('admission_date', 'like', "%{$directorySearch}%");
                    break;

                default:
                    // no-op
                    break;
            }
        } elseif ($directorySearch !== '') {
            // Fallback broad search if user typed something but didn't select a field
            $query->where(function ($q) use ($directorySearch) {
                $q->where('id', $directorySearch)
                    ->orWhere('emergency_contact', 'like', "%{$directorySearch}%")
                    ->orWhere('emergency_contact_relation', 'like', "%{$directorySearch}%")
                    ->orWhere('admission_date', 'like', "%{$directorySearch}%")
                    ->orWhereHas('user', function ($userQuery) use ($directorySearch) {
                        $userQuery->where('first_name', 'like', "%{$directorySearch}%")
                            ->orWhere('last_name', 'like', "%{$directorySearch}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$directorySearch}%"]);
                    });
            });
        }

        $patients = $query
            ->orderBy('id', 'asc')
            ->paginate(25)
            ->withQueryString();

        return view('staff.patients.index', [
            'selectedPatient' => $selectedPatient,
            'infoNameSearch'  => $infoNameSearch,
            'patients'        => $patients,
            'directorySearch' => $directorySearch,
            'searchField'     => $searchField,
            'canEdit'         => $canEdit,
        ]);
    }

    /**
     * Update Group + Admission Date (Admin + Supervisor only).
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
            ->route('staff.patients.index', [
                'info_name' => optional($patient->user)->full_name,
            ])
            ->with('success', 'Patient information updated successfully.');
    }
}
