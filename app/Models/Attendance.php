<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Attendance extends Model
{
    use HasFactory;

    const TYPE_COMMUTE = "commute";
    const TYPE_LEAVE = "leave";

    protected $dates = [
        'commuting_time_1',
        'commuting_time_2',
        'commuting_time_3',
        'leave_time_1',
        'leave_time_2',
        'leave_time_3',
    ];

    protected $fillable = [
        'commuting_time_1',
        'commuting_time_2',
        'commuting_time_3',
        'leave_time_1',
        'leave_time_2',
        'leave_time_3',
        'year_id',
        'month',
        'day',
        'day_of_week',
        // 'substitute_time',
        // 'substitute_day',
        // 'annual_paid_time',
        // 'special_paid_time',
        // 'special_unpaid_time',
        // 'other_unpaid_time',
        // 'reason_for_vacation_id',
        // 'remark'
    ];

    protected $appends = [
        'is_approved'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift1()
    {
        return $this->belongsTo(ShiftPlan::class, 'shift_1_id');
    }
    public function shift2()
    {
        return $this->belongsTo(ShiftPlan::class, 'shift_2_id');
    }
    public function shift3()
    {
        return $this->belongsTo(ShiftPlan::class, 'shift_3_id');
    }
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }
    public function getDateAttribute()
    {
        $year = $this->year;
        if (!$year) return null;
        $month = $this->attributes['month'];

        $yearNumber = floor($year->start / 100);
        if ($month < $year->start % 100) {
            $yearNumber = floor($year->end / 100);
        }
        return Carbon::parse($yearNumber . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $this->attributes['day']));
    }

    public function getIsApprovedAttribute()
    {
        return !empty($this->attributes['approved_at']);
    }
}
