<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caregiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift',
        'assigned_patients',
        'performance_rating',
    ];

    // 🔗 Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rosters()
    {
        return $this->belongsToMany(Roster::class, 'roster_caregiver');
    }

    public function dailyTasks()
    {
        return $this->hasMany(DailyTask::class);
    }
}
