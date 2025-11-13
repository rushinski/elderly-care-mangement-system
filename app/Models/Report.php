<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'admin_id',
        'report_date',
        'missed_tasks',
        'completed_tasks',
        'total_tasks',
        'summary',
    ];

    // 🔗 Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
