<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Slide rates
    private const DAILY_RATE        = 10;
    private const APPOINTMENT_RATE  = 50;
    private const MEDICINE_RATE     = 5;

    /**
     * Main Payment screen (Admin only).
     * - Shows Patient ID / Total Due / New Payment fields.
     */
    public function index(Request $request)
    {
        $patient   = null;
        $account   = null;
        $totalDue  = null;

        if ($request->filled('patient_id')) {
            $patient = Patient::with('user')->find($request->input('patient_id'));

            if ($patient) {
                // One row per patient; create if missing
                $account = Payment::firstOrCreate(
                    ['patient_id' => $patient->id],
                    [
                        'daily_rate'        => self::DAILY_RATE,
                        'appointment_rate'  => self::APPOINTMENT_RATE,
                        'medicine_rate'     => self::MEDICINE_RATE,
                        'days'              => 0,
                        'appointments'      => 0,
                        'total_amount'      => 0,
                        'status'            => 'Unpaid',
                        'payment_date'      => null,
                    ]
                );

                $totalDue = $account->total_amount;
            }
        }

        return view('admin.payments.index', compact('patient', 'account', 'totalDue'));
    }

    /**
     * "Ok" on New Payment: apply payment amount and reduce total due.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'  => 'required|exists:patients,id',
            'amount'      => 'required|numeric|min:0.01',
        ]);

        $account = Payment::firstOrCreate(
            ['patient_id' => $data['patient_id']],
            [
                'daily_rate'        => self::DAILY_RATE,
                'appointment_rate'  => self::APPOINTMENT_RATE,
                'medicine_rate'     => self::MEDICINE_RATE,
                'days'              => 0,
                'appointments'      => 0,
                'total_amount'      => 0,
                'status'            => 'Unpaid',
                'payment_date'      => null,
            ]
        );

        $account->total_amount = max(0, $account->total_amount - $data['amount']);

        if ($account->total_amount == 0) {
            $account->status       = 'Paid';
            $account->payment_date = now();
        } else {
            $account->status = 'Unpaid';
        }

        $account->save();

        return redirect()
            ->route('payments.index', ['patient_id' => $data['patient_id']])
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * "Update" button:
     * For EACH patient, calculate charges from last update date → today:
     *  - $10 * days
     *  - $50 * appointments in that range
     *  - $5  * prescriptions in that range
     */
    public function summary(Request $request)
    {
        $today = Carbon::today();

        // Ensure there is an account row for every patient
        $patients = Patient::all();

        foreach ($patients as $patient) {
            $account = Payment::firstOrCreate(
                ['patient_id' => $patient->id],
                [
                    'daily_rate'        => self::DAILY_RATE,
                    'appointment_rate'  => self::APPOINTMENT_RATE,
                    'medicine_rate'     => self::MEDICINE_RATE,
                    'days'              => 0,
                    'appointments'      => 0,
                    'total_amount'      => 0,
                    'status'            => 'Unpaid',
                    'payment_date'      => null,
                ]
            );

            // "Previous update date" = last payment_date; if null, use created_at
            $lastDate = $account->payment_date
                ? $account->payment_date->copy()
                : $account->created_at->copy()->startOfDay();

            // If already updated today, skip
            if ($lastDate->isSameDay($today)) {
                continue;
            }

            $from = $lastDate->copy();
            $to   = $today->copy();

            $days = $from->diffInDays($to);
            if ($days <= 0) {
                $days = 0;
            }

            // Appointments in [from, to]
            $appointmentsCount = Appointment::where('patient_id', $patient->id)
                ->whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()])
                ->count();

            // Prescriptions created in [from, to] (treat as "medicines")
            $medCount = Prescription::where('patient_id', $patient->id)
                ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
                ->count();

            $dayCharge   = $days * self::DAILY_RATE;
            $apptCharge  = $appointmentsCount * self::APPOINTMENT_RATE;
            $medCharge   = $medCount * self::MEDICINE_RATE;
            $increment   = $dayCharge + $apptCharge + $medCharge;

            if ($increment > 0) {
                $account->daily_rate       = self::DAILY_RATE;
                $account->appointment_rate = self::APPOINTMENT_RATE;
                $account->medicine_rate    = self::MEDICINE_RATE;
                $account->days            += $days;
                $account->appointments    += $appointmentsCount;
                $account->total_amount    += $increment;
                $account->status           = 'Unpaid';
                $account->payment_date     = $today;
                $account->save();
            }
        }

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payments updated based on latest activity.');
    }

    // You can leave edit/update/destroy empty or keep the old ones if you still use them elsewhere.
    public function edit(Payment $payment) {}
    public function update(Request $request, Payment $payment) {}
    public function destroy(Payment $payment) {}
}