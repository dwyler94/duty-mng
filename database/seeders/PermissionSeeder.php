<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            Permissions::LOGIN, // 'ログイン',
            Permissions::STAMP, // '打刻',
            Permissions::ATTENDANCE_STATUS_LIST, // '勤怠状況一覧',
            Permissions::CHECK_ATTENDANCE_STATUS, // '出勤状況確認',
            Permissions::ATTENDANCE_MONTHLY_STAT, // '勤怠管理-月間集計',
            Permissions::SHIFT_PLAN, // 'シフト計画',
            Permissions::SHIFT_CREATE, // 'シフト作成',
            Permissions::ATTENDANCE_STAT, // '勤務集計',
            Permissions::MASTER_SETTING, // 'マスタ管理',
            Permissions::CONFIG_SETTING, // '設定管理',
        ];
        $i = 1;
        foreach($data as $item)
        {
            $t = new Permission();
            $t->name = $item;
            $t->id = $i;
            $t->save();
            $i++;
        }
    }
}
