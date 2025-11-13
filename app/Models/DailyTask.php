<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'roster_id',
        'patient_id',
        'task_name',
        'completed',
        'completed_at',
        'remarks',
    ];

    // 🔗 Relationships
    public function roster()
    {
        return $this->belongsTo(Roster::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
