<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'role_id',
        'password',
    ];

    protected $hidden = [
        'remember_token',
    ];

    // Optional: if you ever cast to JSON
    // protected $appends = ['full_name', 'age'];

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

    // --------- Helpers ---------

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return Carbon::parse($this->date_of_birth)->age;
    }
}
