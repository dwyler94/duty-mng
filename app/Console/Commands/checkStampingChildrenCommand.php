<?php

namespace App\Console\Commands;

use App\Models\Children;
use App\Models\Holiday;
use App\Models\Notification;
use App\Models\ChildrenAttendence;
use App\Models\QrTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class checkStampingChildrenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *      exec: php artisan command:checkStampingChildren
     * @var string
     */
    protected $signature = 'command:checkStampingChildren {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checkStampingChildrenCommand';

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
        $date = $this->argument("date");
        if (!empty($date)) {
            if (!preg_match('/\A[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}\z/', $date)) {
                return false;
            }
            $exp = explode('-', $date);
            if (!checkdate((int)$exp[1], (int)$exp[2], (int)$exp[0])) {
                return false;
            }
            $ymd = date("Ymd", strtotime($date));
        } else {
            $date = date('Y-m-d', strtotime('-1 day'));
            $ymd = date("Ymd", strtotime('-1 day'));
        }
        $this->info($date);
        $this->info($ymd);

        // 祝日は処理しない
        $holiday = Holiday::where('date', '=', $date)->first();
        if (!empty($holiday) && !empty($holiday->id)) {
            $this->info("祝日の為処理終了:" . $holiday->name ?? null);
            return true;
        }
        // 日曜は処理しない
        $w = date('w', strtotime($date));
        if (empty($w)) {
            $this->info("日曜日の為処理終了:");
            return true;
        }

        // 該当日クリア
        Notification::where('date', '=', $date)->delete();

        // 登園あり降園なし
        $check1 = ChildrenAttendence::where('date', '=', $date)
            ->join('children', 'children.id', '=', 'children_attendences.child_id')
            ->where('commuting_time', '<>', null)->where('leave_time', '=', null)
            ->where('admission_date', '<=', $date)
            ->where('canceled_at', '=', null)
            ->where(function ($query) use ($date) {
                $query->orWhere('exit_date', '>=', $date)->orWhere('exit_date', '=', null);
            })
            ->select('children_attendences.*', 'children.name', 'children.id as child_id')->get();

        foreach ($check1 as $item) {
            $notification = Notification::where('date', $date)->where('child_id', $item->child_id)
                ->where('process_flag', false)->first();
            if (!empty($notification->id)) {
                continue;
            }
            Notification::create([
                'date' => $date,
                'child_id' => $item->child_id,
                'message' =>  $item->name . 'さんの打刻（降園）がありません。',
                'url' => '/child?date='.$date.'&',
            ]);
        }

        // 登園なし降園あり
        $check2 = ChildrenAttendence::where('date', '=', $date)
            ->join('children', 'children.id', '=', 'children_attendences.id')
            ->where('commuting_time', '=', null)->where('leave_time', '<>', null)
            ->where('admission_date', '<=', $date)
            ->where('canceled_at', '=', null)
            ->where(function ($query) use ($date) {
                $query->orWhere('exit_date', '>=', $date)->orWhere('exit_date', '=', null);
            })
            ->select('children_attendences.*', 'children.name', 'children.id as child_id')->get();

        foreach ($check2 as $item) {
            $notification = Notification::where('date', $date)->where('child_id', $item->child_id)
                ->where('process_flag', false)->first();
            if (!empty($notification->id)) {
                continue;
            }
            Notification::create([
                'date' => $date,
                'child_id' => $item->child_id,
                'message' =>  $item->name . 'さんの打刻（登園）がありません。',
                'url' => '/child?date='.$date.'&',
            ]);
        }

        // 打刻3回以上
        $check3 = QrTransaction::where('ymd', '=', $ymd)
            ->join('children', 'children.qr', '=', 'qr_transactions.qr')
            ->groupBy('children.id', 'qr_transactions.ymd')
            ->having('count', '>', 2)
            ->select('qr_transactions.qr', 'children.id', 'children.name', 'children.id as child_id')
            ->selectRaw('COUNT(qr_transactions.qr) as count')
            ->get();

        foreach ($check3 as $item) {
            $qrTransaction = QrTransaction::where('ymd', '=', $ymd)->where('qr', $item->qr)->get();
            $string = [];
            foreach ($qrTransaction as $qr) {
                $string[] = Carbon::parse($qr->created_at)->format('H:i:s');
            }
            if (!empty($string) && COUNT($string) > 0) {
                $string = '('.implode(',', $string).')';
            }
            $notification = Notification::where('date', $date)->where('child_id', $item->child_id)
                ->where('process_flag', false)->first();
            if (!empty($notification->id)) {
                continue;
            }
            Notification::create([
                'date' => $date,
                'child_id' => $item->id,
                'message' =>  Carbon::parse($date)->format('y.m.d') . ' ' . $item->name . 'さんの打刻が'.$item->count.'回あります。' . $string,
                'url' => '/child?date='.$date.'&',
            ]);
        }

        // 登園なし降園なし（欠席除く）
        $check4 = Children::leftJoin('children_attendences', function($join) use($date) {
                $join->on('children.id', '=', 'children_attendences.child_id')->where('date', '=', $date);
            })
            ->leftJoin('childcare_plan_days', function($join) use($date) {
                $join->on('children.id', '=', 'childcare_plan_days.child_id')
                    ->where('childcare_plan_days.date', '=', $date);
            })
            ->where('children.deleted_at', '=', null)
            ->where('commuting_time', '=', null)->where('leave_time', '=', null)
            ->where('reason_for_absence_id', '=', null)
            ->where('admission_date', '<=', $date)
            ->where('canceled_at', '=', null)
            ->where(function ($query) use ($date) {
                $query->orWhere('exit_date', '>=', $date)->orWhere('exit_date', '=', null);
            })
            ->where('absent_id', '=', null)
            ->select('children_attendences.*', 'children.name', 'children.id as child_id')->get();

        foreach ($check4 as $item) {
            $notification = Notification::where('date', $date)->where('child_id', $item->child_id)
                ->where('process_flag', false)->first();
            if (!empty($notification->id)) {
                continue;
            }

            Notification::create([
                'date' => $date,
                'child_id' => $item->child_id,
                'message' =>  $item->name . 'さんの打刻（登園&降園）がありません。欠席除く',
                'url' => '/child?date='.$date.'&',
            ]);
        }

        return true;
    }
}
