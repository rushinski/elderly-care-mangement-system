<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'name',            // keep for now; we’ll set it from first/last
        'email',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'family_code',
        'emergency_contact',
        'emergency_contact_relation',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function getNameAttribute()
    {
        if ($this->first_name || $this->last_name) {
            return trim("{$this->first_name} {$this->last_name}");
        }

        return $this->email; // fallback
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function caregiver()
    {
        return $this->hasOne(Caregiver::class);
    }

    public function supervisor()
    {
        return $this->hasOne(Supervisor::class);
    }
}
