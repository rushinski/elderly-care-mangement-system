<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'relationship',
        'email',
        'phone',
        'family_code',
    ];

    // 🔗 Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
