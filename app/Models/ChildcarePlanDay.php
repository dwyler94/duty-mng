<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildcarePlanDay extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    protected $fillable = [
        'date', 'child_id', 'start_time', 'end_time', 'absent_id'
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
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
        if (!$this->attributes['start_time'] || !$this->attributes['end_time'])
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
