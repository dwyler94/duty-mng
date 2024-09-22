<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'open_time',
        'close_time',
        'open_time_short',
        'close_time_short',
        'capacity',
        'appropriate_number_0',
        'appropriate_number_1',
        'appropriate_number_2',
        'appropriate_number_3',
        'appropriate_number_4',
        'appropriate_number_5',
        'business_type_id',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
