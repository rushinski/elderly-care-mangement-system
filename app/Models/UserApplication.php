<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'role_id',
        'password',
        'date_of_birth',
        'family_code',
        'emergency_contact',
        'emergency_contact_relation',
        'status',
        'approved_by',
        'approved_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isPatient(): bool
    {
        return $this->role && $this->role->name === 'Patient';
    }
}
