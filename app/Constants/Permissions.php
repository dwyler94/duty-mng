<?php
namespace App\Constants;

class Permissions {
    const LOGIN = 'login';                                      // ログイン
    const STAMP = 'stamp';                                      // 打刻
    const ATTENDANCE_STATUS_LIST = 'attendance_status_list';    // 勤怠状況一覧
    const CHECK_ATTENDANCE_STATUS = 'check_attendance_status';  // 出勤状況確認
    const ATTENDANCE_MONTHLY_STAT = 'attendance_monthly_stat';  // 勤怠管理-月間集計
    const SHIFT_PLAN = 'shift_plan';                            // シフト計画
    const SHIFT_CREATE = 'shift_create';                        // シフト作成
    const ATTENDANCE_STAT = 'attendance_stat';                  // 勤務集計
    const MASTER_SETTING = 'master_setting';                    // マスタ管理
    const CONFIG_SETTING = 'config_setting';                    // 設定管理
}
