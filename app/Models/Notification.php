<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'date', 'child_id', 'message', 'url'
    ];

    /**
     * 通知一覧検索
     * @param $conditions 検索条件
     * @return array
     */
    public function getNotificationList($conditions = [])
    {
        $where = $orWhere = [];
        foreach ($conditions as $key => $item) {
            if (empty($item)) {
                continue;
            }
            if (array_key_exists('browsing_flag', $conditions)) {
                $where[] = ['browsing_flag', '=', $item];
            }
            if (array_key_exists('process_flag', $conditions)) {
                $where[] = ['process_flag', '=', $item];
            }
        }

        return $this::select(
            'notifications.*'
        )
            ->leftJoin('children', 'children.id', 'notifications.child_id')
            ->where($where)
            ->where(function ($query) use ($orWhere) {
                foreach ($orWhere as $item) {
                    $query->orWhere($item[0],$item[1],$item[2]);
                }
            })
            ->orderBy('notifications.created_at', 'desc')
            ->get();
    }
}
