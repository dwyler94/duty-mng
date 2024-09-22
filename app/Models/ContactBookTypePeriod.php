<?php

namespace App\Models;

use App\Models\Child;
use App\Models\ChildrenClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ContactBookTypePeriod extends Model
{
    use HasFactory;

    const TYPE_0 = 1;
    const TYPE_1_2 = 2;
    const TYPE_3_5 = 3;

    protected $fillable = [
        'child_id',
        'type',
        'start_date'
    ];

    public static function appendType($childID, $type, $appendDate)
    {
        // 未来の連絡帳種別設定を削除
        self::where('child_id', $childID)
            ->where('start_date', '>', $appendDate)
            ->delete();

        // 直前の連絡帳種別設定を取得
        $beforeTypePeriod = self::where('child_id', $childID)
            ->where('start_date', '<=', $appendDate)
            ->latest('start_date')
            ->first();
        ;

        if (!empty($beforeTypePeriod)) {
            // 直前から種別が変わらない場合は何もしない
            if ($beforeTypePeriod->type == $type) {
                return;
            }

            // 同じ日に種別設定されている場合は上書き
            if ((new Carbon($beforeTypePeriod->start_date))->isSameDay($appendDate)) {
                $beforeTypePeriod->type = $type;
                $beforeTypePeriod->save();

                return;
            }
        }

        self::create([
            'child_id' => $childID,
            'type' => $type,
            'start_date' => $appendDate,
        ]);
    }

    public static function getType(Child $child, $date)
    {
        return self::where('child_id', $child->id)
            ->where('start_date', '<=', $date)
            ->latest('start_date')
            ->value('type');
    }

    public static function typeByBirthday($birthday)
    {
        $age = Carbon::parse($birthday)->age;

        switch ($age) {
            case 0:
                return self::TYPE_0;
            case 1:
            case 2:
                return self::TYPE_1_2;
            case 3:
            case 4:
            case 5:
                return self::TYPE_3_5;
        }
    }

    public static function typeByClassID($classID)
    {
        switch ($classID) {
            case ChildrenClass::AGE_0:
                return self::TYPE_0;
            case ChildrenClass::AGE_1:
            case ChildrenClass::AGE_2:
                return self::TYPE_1_2;
            case ChildrenClass::AGE_3:
            case ChildrenClass::AGE_4:
            case ChildrenClass::AGE_5:
                return self::TYPE_3_5;
        }
    }
}
