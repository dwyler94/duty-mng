<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
}
