<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Office extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'number',
        'region_id',
        'industry_group_id',
        'office_group_id',
        'rest_deduction_id',
        'type'
    ];

    protected $appends = [
        'is_headquarter',
        'is_nursery'
    ];

    const TYPE_OTHERS = 1;
    const TYPE_NURSERY = 2;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function group()
    {
        return $this->belongsTo(OfficeGroup::class, 'office_group_id', 'id');
    }

    public function getIsHeadquarterAttribute()
    {
        if (!$this->attributes['name']) return false;
        return Str::endsWith($this->attributes['name'], '本社');
    }

    public function getIsNurseryAttribute()
    {
        if (!$this->attributes['name']) return false;
        return $this->type === self::TYPE_NURSERY;
    }

    public function office_information()
    {
        return $this->hasOne(OfficeInformation::class)->ofMany('created_at', 'max');
    }

    public function getCapacityAttribute()
    {
        if ($this->office_information) return $this->office_information->capacity;
        return null;
    }
    public function getInformationByMonth($month) // YYYY-MM
    {
        $baseDate = Carbon::parse($month . '-01');
        $firstDate = $baseDate->format('Y-m-d');
        $lastDate = $baseDate->endOfMonth()->format('Y-m-d');

        return OfficeInformation::where(['office_id' => $this->id])
            ->where(function($query) use ($firstDate) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $firstDate);
            })
            ->where(function($query) use ($firstDate) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $firstDate);
            })
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getCapacityByMonth($month)
    {
        $office_information = $this->getInformationByMonth($month);
        if ($office_information) return $office_information->capacity;
        return null;
    }

    public function getCertifTypeEnabledAttribute()
    {
        if ($this->office_information)
        {
            return $this->office_information->business_type_id === BusinessType::TYPE_2 || $this->office_information->business_type_id === BusinessType::TYPE_3;
        }
        return false;
    }
}
