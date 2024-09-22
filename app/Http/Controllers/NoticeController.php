<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NoticeController extends Controller
{
    public function notice(int $id)
    {
        if (!empty($id)) {
            $notification = Notification::where('id', $id)->first();
            if (!empty($notification->id)) {
                Notification::where('id', $id)->update([
                    'browsing_flag' => true,
                    'browsing_datetime' => date('YmdHis'),
                    'browsing_user_id' => \Auth::id(),
                ]);
                return redirect($notification->url);
            }
        }
        abort(404);
    }

    public function noticeFinish(int $id, $date, $bool)
    {
        if (!empty($id) && !empty($date)) {
            $notification = Notification::where('child_id', $id)->whereDate('date', '=', date('Y-m-d',strtotime($date)))->first();
            if (!empty($notification->id)) {
                Notification::where('id', $notification->id)->update([
                    'process_flag' => !empty($bool) ? true : false,
                    'process_datetime' => date('YmdHis'),
                    'process_user_id' => \Auth::id(),
                ]);
                return redirect($notification->url);
            }
        }
        abort(404);
    }
}
