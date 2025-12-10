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
        'caregiver_1',
        'caregiver_2',
        'caregiver_3',
        'caregiver_4',
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
    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class);
    }

    public function caregiver1()
    {
        return $this->belongsTo(User::class, 'caregiver_1');
    }

    public function caregiver2()
    {
        return $this->belongsTo(User::class, 'caregiver_2');
    }

    public function caregiver3()
    {
        return $this->belongsTo(User::class, 'caregiver_3');
    }

    public function caregiver4()
    {
        return $this->belongsTo(User::class, 'caregiver_4');
    }


    // Legacy alias so old code using $roster->user still works
    public function user()
    {
        return $this->belongsTo(User::class, 'caregiver_1_id');
    }
}
