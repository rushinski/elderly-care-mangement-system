<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',    // users.id (Supervisor)
        'doctor_id',        // users.id (Doctor user)
        'caregiver_1_id',   // users.id
        'caregiver_2_id',   // users.id
        'caregiver_3_id',   // users.id
        'caregiver_4_id',   // users.id
        'date',
    ];

    // Relationships (all to User – role dictates type)
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function doctorUser()
    {
        // user record for the doctor on this roster
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function caregiver1()
    {
        return $this->belongsTo(User::class, 'caregiver_1_id');
    }

    public function caregiver2()
    {
        return $this->belongsTo(User::class, 'caregiver_2_id');
    }

    public function caregiver3()
    {
        return $this->belongsTo(User::class, 'caregiver_3_id');
    }

    public function caregiver4()
    {
        return $this->belongsTo(User::class, 'caregiver_4_id');
    }

    /**
     * Backward compatibility when legacy code calls $roster->user
     * (treat caregiver_1 as primary staff)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'caregiver_1_id');
    }
}
