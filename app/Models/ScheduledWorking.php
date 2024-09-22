<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledWorking extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_id',
        'month',
        'days',
        'hours',
        'office_id'
    ];
}
