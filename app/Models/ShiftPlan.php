<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftPlan extends Model
{
    use HasFactory;

    protected $dates = ['date'];

    protected $fillable = [
        'day_of_week',
        'date',
        'user_id',
        'working_hours_id',
        'start_time',
        'end_time',
        'rest_start_time',
        'rest_end_time',
        'vacation_reason_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRestStartDateTimeAttribute()
    {
        if (!$this->start_time || !$this->end_time || !$this->rest_start_time || !$this->rest_end_time)
        {
            return null;
        }
        if ($this->start_time > $this->rest_start_time)
        {
            $date = clone $this->date;
            $date->addDay();
            return $date->format('Y-m-d') . 'T' . $this->rest_start_time;
        }
        return $this->date->format('Y-m-d') . 'T' . $this->rest_start_time;
    }
    public function getRestEndDateTimeAttribute()
    {
        if (!$this->start_time || !$this->end_time || !$this->rest_start_time || !$this->rest_end_time)
        {
            return null;
        }
        if ($this->start_time > $this->rest_end_time || $this->rest_start_time > $this->rest_end_time)
        {
            $date = clone $this->date;
            $date->addDay();
            return $date->format('Y-m-d') . 'T' . $this->rest_end_time;
        }
        return $this->date->format('Y-m-d') . 'T' . $this->rest_end_time;
    }

    public function getStartDateTimeAttribute()
    {
        if (!$this->start_time || !$this->end_time)
        {
            return null;
        }
        return $this->date->format('Y-m-d') . 'T' . $this->start_time;
    }
    public function getEndDateTimeAttribute()
    {
        if (!$this->start_time || !$this->end_time)
        {
            return null;
        }
        if ($this->start_time > $this->end_time)
        {
            $date = clone $this->date;
            $date->addDay();
            return $date->format('Y-m-d') . 'T' . $this->end_time;
        }
        return $this->date->format('Y-m-d') . 'T' . $this->end_time;
    }
}
