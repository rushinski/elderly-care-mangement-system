<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',        // doctors.id
        'patient_id',       // patients.id
        'appointment_date', // DATE
        'status',
        'notes',
    ];

    // default if DB default ever changes
    protected $attributes = [
        'status' => 'Scheduled',
    ];

    // Relationships
    public function doctor()
    {
        // doctor_id -> doctors.id
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        // patient_id -> patients.id
        return $this->belongsTo(Patient::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }
}
