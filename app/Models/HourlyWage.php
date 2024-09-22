<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HourlyWage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'office_id',
    ];

    public function getStartTimeAttribute()
    {
        if (!empty($this->attributes['start_time']))
        {
            $arr = explode(':', $this->attributes['start_time']);
            return $arr[0] . ':' . $arr[1];
        } else {
            return null;
        }
    }
    public function getEndTimeAttribute()
    {
        if (!empty($this->attributes['end_time']))
        {
            $arr = explode(':', $this->attributes['end_time']);
            return $arr[0] . ':' . $arr[1];
        } else {
            return null;
        }
    }
}
