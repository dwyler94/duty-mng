<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'start_date',
        'end_date',
        'overtime_pay',
        'salary_deduction',
        'application_deadline'
    ];

}
