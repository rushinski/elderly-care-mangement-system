<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'caregiver_id',
        'date',
        'shift',
        'notes'
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }

    // ✅ Add this:
    public function user()
    {
        // Fallback relationship for older code referencing `$roster->user`
        // It links to caregiver by default.
        return $this->belongsTo(User::class, 'caregiver_id');
    }
}
