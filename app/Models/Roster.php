<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Roster extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',   // users.id
        'doctor_id',       // users.id
        'caregiver_1_id',  // users.id
        'caregiver_2_id',  // users.id
        'caregiver_3_id',  // users.id
        'caregiver_4_id',  // users.id
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // user record for the doctor on that day
    public function doctor()
    {
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

    // Legacy alias so old code using $roster->user still works
    public function user()
    {
        return $this->belongsTo(User::class, 'caregiver_1_id');
    }
}
