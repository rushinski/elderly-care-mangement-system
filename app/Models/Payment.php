<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'daily_rate',
        'appointment_rate',
        'medicine_rate',
        'days',
        'appointments',
        'total_amount',
        'status',
        'payment_date',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Auto-calculate total on save
    protected static function booted()
    {
        static::saving(function ($payment) {
            $payment->total_amount = 
                ($payment->days * $payment->daily_rate) +
                ($payment->appointments * $payment->appointment_rate) +
                $payment->medicine_rate;
        });
    }
}
