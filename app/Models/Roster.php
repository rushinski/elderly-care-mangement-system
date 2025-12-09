<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'supervisor_id',
        'doctor_id',
        'caregiver_1',
        'caregiver_2',
        'caregiver_3',
        'caregiver_4',
        'date',
    ];

    /**
     * Relationships
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
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

    /**
     * Backward compatibility for any legacy code 
     * that references $roster->user (assumes caregiver_1 as primary staff link)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'caregiver_1');
    }
}
