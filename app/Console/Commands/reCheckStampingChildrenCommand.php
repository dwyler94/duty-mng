<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use App\Models\Notification;
use App\Models\ChildrenAttendence;
use Illuminate\Console\Command;

class reCheckStampingChildrenCommand extends Command
{
    CONST LIMIT_DATE = 30;

    /**
     * The name and signature of the console command.
     *      exec: php artisan command:reCheckStampingChildren
     * @var string
     */
    protected $signature = 'command:reCheckStampingChildren';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reCheckStampingChildrenCommand';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        for ($i=2; $i <= self::LIMIT_DATE; $i++) {
            $date = date('Y-m-d', strtotime('-' . $i . ' day'));
            $ymd = date("Ymd", strtotime('-' . $i . ' day'));

            $this->info($date);
            $this->info($ymd);

            // 祝日は処理しない
            $holiday = Holiday::where('date', '=', $date)->first();
            if (!empty($holiday) && !empty($holiday->id)) {
                continue;
            }
            // 日曜は処理しない
            $w = date('w', strtotime($date));
            if (empty($w)) {
                continue;
            }

            // 降園なし（手動アラートOFF）
            $check1 = ChildrenAttendence::where('children_attendences.date', '=', $date)
                ->join('children', 'children.id', '=', 'children_attendences.child_id')
                ->join('notifications', function($join) use($date) {
                    $join->on('notifications.child_id', '=', 'children.id')
                        ->where('notifications.date', '=', $date)->where('notifications.process_flag', '=', true);
                })
                ->where('commuting_time', '<>', null)->where('leave_time', '=', null)
                ->where('reason_for_absence_id', '=', null)
                ->where('canceled_at', '=', null)
                ->where(function ($query) use ($date) {
                    $query->orWhere('exit_date', '>=', $date)->orWhere('exit_date', '=', null);
                })
                ->select('children_attendences.*', 'children.name')->get();

            foreach ($check1 as $item) {
                $notification = Notification::where('date', $date)->where('child_id', $item->child_id)->first();
                if (!empty($notification->id)) {
                    Notification::where('id', $notification->id)->update([
                        'message' =>  $item->name . 'さんの打刻（降園）がありません。[再]',
                        'url' => '/child?date='.$date.'&',
                        'process_flag' => false,
                    ]);
                } else {
                    Notification::create([
                        'date' => $date,
                        'child_id' => $item->child_id,
                        'message' =>  $item->name . 'さんの打刻（降園）がありません。[再]',
                        'url' => '/child?date='.$date.'&',
                    ]);
                }
            }
        }
        return true;
    }
}
