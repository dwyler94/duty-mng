<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'fraction_commuting_time',
        'fraction_leave_time',
        'fraction_behind_time',
        'fraction_leave_early',
        'round_up_commuting_time',
        'truncate_leave_time',
        'round_up_behind_time',
        'truncate_leave_early',
        'consecutive_work',
    ];
}
