<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'access_level'];

    // Names used in middleware / sidebar and seeded with fixed access levels
    public const SYSTEM_ROLES = [
        'Admin',
        'Supervisor',
        'Doctor',
        'Caregiver',
        'Patient',
        'Family',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function defaultAccessLevel(): int
    {
        // new custom roles default below all current system roles
        return 6;
    }
}
