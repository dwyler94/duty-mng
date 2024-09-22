<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'company_name',
        'free_of_charge',
        'certificate_of_payment',
        'certificate_expiration_date',
        'tax_exempt_household',
        'remarks',
        'child_id',
        'no',
        'certification_type'
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
