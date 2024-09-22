<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildcareDiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'office_id', 'children_class_id', 'weather', 'reason_for_absence',
        'aim', 'activity_content', 'consideration', 'evaluation_reflection',
        'health_status', 'remarks', 'special_note'
    ];
}
