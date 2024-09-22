<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonForAbsence extends Model
{
    use HasFactory;

    const REASON_CORONA = 1;
    const REASON_PRIVATE = 2;
    const REASON_KIBIKI = 3;
    const REASON_SICK = 4;
    const REASON_SUSPENSION = 5;
    const REASON_VACATION = 6;
    const REASON_HOLIDAY = 7;
    const REASON_DISASTER = 8;
}
