<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildcarePlan extends Model
{
    use HasFactory;

    const PLAN_UNREGISTERED = 0;
    const PLAN_REGISTERED = 1;

    protected $fillable = [
        'day_of_weeks', 'children_id', 'start_time', 'end_time', 'excluding_holidays', 'create_user_id', 'update_user_id'
    ];

    protected $casts = [
        'day_of_weeks'   =>  'array'
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
