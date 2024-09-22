<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationClass extends Model
{
    use HasFactory;

    const TYPE_BEHIND_TIME = 1;
    const TYPE_LEAVE_EARLY = 2;
    const TYPE_OTHER = 3;
}
