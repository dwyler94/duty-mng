<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    protected $fillable = [
        'attendance_id',
        'application_class_id',
        'reason',
        'time_before_correction',
        'time_after_correction',
        'reason_id',
    ];

    protected $dates = [
        'time_before_correction',
        'time_after_correction'
    ];

    protected $appends = [
        'is_approved',
        'is_rejected'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
    public function getIsApprovedAttribute()
    {
        if (empty($this->attributes['status'])) return false;
        return $this->attributes['status'] === self::STATUS_APPROVED;
    }
    public function getIsRejectedAttribute()
    {
        if (empty($this->attributes['status'])) return false;
        return $this->attributes['status'] === self::STATUS_REJECTED;
    }
}
