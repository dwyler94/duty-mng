<?php

namespace App\Models;

use App\Models\Child;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ChildrenClassPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'class_id',
        'start_date',
    ];

    public static function appendClass($childID, $classID, $appendDate)
    {
        // 未来のクラス設定を削除
        self::where('child_id', $childID)
            ->where('start_date', '>', $appendDate)
            ->delete();

        // 直前のクラス設定を取得
        $beforeClassPeriod = self::where('child_id', $childID)
            ->where('start_date', '<=', $appendDate)
            ->latest('start_date')
            ->first();
        ;

        if (!empty($beforeClassPeriod)) {
            // 直前からクラスが変わらない場合は何もしない
            if ($beforeClassPeriod->class_id == $classID) {
                return;
            }

            // 同じ日にクラス設定されている場合は上書き
            if ((new Carbon($beforeClassPeriod->start_date))->isSameDay($appendDate)) {
                $beforeClassPeriod->class_id = $classID;
                $beforeClassPeriod->save();

                return;
            }
        }

        self::create([
            'child_id' => $childID,
            'class_id' => $classID,
            'start_date' => $appendDate,
        ]);
    }

    public static function latestClassIDSubClosure($date)
    {
        return function ($query) use ($date) {
            $query->select('class_id')
                ->from('children_class_periods')
                ->whereColumn('child_id', 'children.id')
                ->where('start_date', '<=', $date)
                ->latest('start_date')
                ->limit(1);
        };
    }
}
