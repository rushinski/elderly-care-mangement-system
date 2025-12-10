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

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
