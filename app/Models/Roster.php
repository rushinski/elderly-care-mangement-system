<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'doctor_id',
        'caregiver_id',
        'date',
        'shift',
        'notes',
    ];

    // 🔗 Relationships
    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class);
    }

    public function dailyTasks()
    {
        return $this->hasMany(DailyTask::class);
    }
}
