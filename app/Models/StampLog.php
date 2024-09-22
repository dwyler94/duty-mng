<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampLog extends Model
{
    use HasFactory;

    const RESULT_PENDING = 0;
    const RESULT_SUCCESS = 1;
    const RESULT_FAILED = 2;

    protected $fillable = [
        'type',
        'user_id'
    ];
    protected $dates = ['created_at', 'updated_at'];
}
