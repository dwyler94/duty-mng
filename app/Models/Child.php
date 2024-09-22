<?php

namespace App\Models;

use App\Scopes\ChildCancelScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

class Child extends Authenticatable
{
    use HasFactory;
    use SoftDeletes, HasApiTokens;

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    protected $fillable = [
        'name',
        'number',
        'password',
        'email',
        'office_id',
        'enrollment_class',
        'qr',
        'gender',
        'birthday',
        'class_id',
        'admission_date',
        'exit_date',
        "canceled_at"
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new ChildCancelScope);
    }

    public function child_info()
    {
        return $this->hasOne(ChildInformation::class, 'child_id', 'id')->ofMany('created_at', 'max');
    }
    public function getChildInfoByMonthForApplication($month)
    {
        $baseDate = Carbon::parse($month . '-01');
        $firstDate = $baseDate->format('Y-m-d');
        $lastDate = $baseDate->endOfMonth()->format('Y-m-d');
        $date = $lastDate;
        if (Carbon::now()->format('Y-m') === $month) {
            $date = $firstDate;
        }
        return ChildInformation::where(['child_id' => $this->id])
            ->where(function($query) use ($date) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $date);
            })
            ->where(function($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->first();
    }
    public function child_class()
    {
        return $this->belongsTo(ChildrenClass::class, 'class_id', 'id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function getRemarksAttribute()
    {
        if (!$this->child_info) return '';
        return $this->child_info->remarks;
    }

    public function getFreeOfChargeLabelAttribute()
    {
        if (!$this->child_info) return '';
        return $this->child_info->free_of_charge ? '対象' : '対象外';
    }
    public function getCertificateOfPaymentLabelAttribute()
    {
        if (!$this->child_info) return '';
        return $this->child_info->certificate_of_payment ? '有り' : '無し';
    }
    public function getCertificateExpirationDateLabelAttribute()
    {
        if (!$this->child_info) return '';
        if (!$this->child_info->certificate_expiration_date) return '';
        return Carbon::parse($this->child_info->certificate_expiration_date)->format('Y-m-d');
    }
    public function getTaxExemptHouseholdLabelAttribute()
    {
        if (!$this->child_info) return '';
        return $this->child_info->tax_exempt_household ? '〇' : '';
    }

    public function getCompanyNameAttribute()
    {
        if (!$this->child_info) return '';
        return $this->child_info->company_name ? $this->child_info->company_name : '';
    }

    public function dailyPlans()
    {
        return $this->hasMany(ChildcarePlanDay::class);
    }
}
