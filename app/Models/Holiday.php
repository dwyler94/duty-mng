<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $appends = [
        'day'
    ];
    public function getDayAttribute()
    {
        if ($this->date) {
            return Carbon::parse($this->date)->day;
        }
        return null;
    }
}
