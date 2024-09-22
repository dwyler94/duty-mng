<?php
namespace App\Constants;

class Roles {
    const ADMIN = 1;             // ログイン
    const REGION_MANAGER = 2;    // 打刻
    const OFFICE_MANAGER = 3;    // 勤怠状況一覧
    const USER_A = 4;          // 出勤状況確認
    const USER_B = 5;          // 勤怠管理-月間集計
}
