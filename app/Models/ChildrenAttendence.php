<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenAttendence extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'commuting_time',
        'leave_time',
        'reason_for_absence_id',
        'absence',
        'behind_time',
        'leave_early',
        'extension',
        'previous_extension',
        'child_id',
        'year_id',
        'month',
        'day'
    ];
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
    public function reason_for_absence()
    {
        return $this->belongsTo(ReasonForAbsence::class);
    }
    public function getExtensionAttribute()
    {
        if ($this->attributes['extension']) {
            $segs = explode(':', $this->attributes['extension']);
            return $segs[0] . ':' . $segs[1];
        }
        return '';
    }
    public function getPreviousExtensionAttribute()
    {
        if ($this->attributes['previous_extension']) {
            $segs = explode(':', $this->attributes['previous_extension']);
            return $segs[0] . ':' . $segs[1];
        }
        return '';
    }
    public function getExtensionInMinuteAttribute()
    {
        if ($this->attributes['extension']) {
            $segs = explode(':', $this->attributes['extension']);
            return $segs[0] * 60 + $segs[1];
        } else {
            return 0;
        }
    }
}
