<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenClass extends Model
{
    use HasFactory;

    const AGE_0 = 1;
    const AGE_1 = 2;
    const AGE_2 = 3;
    const AGE_3 = 4;
    const AGE_4 = 5;
    const AGE_5 = 6;

    protected $fillable = [
        'name', 'create_user_id'
    ];
}
