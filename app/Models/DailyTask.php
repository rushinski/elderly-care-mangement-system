<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'roster_id',   // rosters.id
        'patient_id',  // patients.id
        'task_date',
        'task_type',
        'task_name',
        'completed',
    ];

    public const TASK_MORNING_MEDICINE   = 'morning_medicine';
    public const TASK_AFTERNOON_MEDICINE = 'afternoon_medicine';
    public const TASK_NIGHT_MEDICINE     = 'night_medicine';
    public const TASK_BREAKFAST          = 'breakfast';
    public const TASK_LUNCH              = 'lunch';
    public const TASK_DINNER             = 'dinner';

    public function roster()
    {
        return $this->belongsTo(Roster::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
