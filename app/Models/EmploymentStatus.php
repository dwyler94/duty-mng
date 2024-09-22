<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentStatus extends Model
{
    use HasFactory, SoftDeletes;

    const NORMAL = 1;
    const SHORT_TIME = 2;
    const PART_TIME = 3;

    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
