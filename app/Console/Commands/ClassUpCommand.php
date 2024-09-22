<?php

namespace App\Console\Commands;

use App\Models\Child;
use App\Models\ChildrenClass;
use App\Models\ChildrenClassPeriod;
use App\Models\ContactBookTypePeriod;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ClassUpCommand extends Command
{
    const CLASS_START_DATE = '04/01';

    protected $description = "Automatically up children's classes";
    protected $signature = 'app:class-up';

    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        /**
         * STEP1 クラス開始日判定
         */
        $this->line('STEP1: クラス開始日判定');

        $now = Carbon::now();

        if (!$now->isSameDay(new Carbon(self::CLASS_START_DATE))) {
            return $this->error('クラス開始日ではありません。');
        }

        $this->info('Success');
        $this->line('');

        /**
         * STEP2 対象園児取得
         */
        $this->line('STEP2: 対象園児取得');
        $this->line('取得開始・・・');

        $query = Child::where('deleted_at', null)
            ->where(function ($query) use ($now) {
                $query->whereNull('exit_date')
                    ->orWhere('exit_date', '>', $now);
            })
            ->orderBy('id')
        ;

        if ($query->count() == 0) {
            return $this->error('対象園児を取得できませんでした。');
        }

        $this->info('Success');
        $this->line('');

        /**
         * STEP3 自動昇級
         */
        $this->line('STEP3: 自動昇級');
        $this->line('処理開始・・・');

        DB::beginTransaction();

        $query->chunkById(10, function ($children) use ($now) {
            foreach ($children as $child) {
                if ($child->birthday === null || $child->class_id === null) {
                    continue;
                }

                $age = Carbon::parse($child->birthday)->age;
                $beforeClassID = $child->class_id;
                $afterClassID = constant(ChildrenClass::class . "::AGE_{$age}");

                $child->class_id = $afterClassID;
                $child->save();

                ChildrenClassPeriod::appendClass($child->id, $afterClassID, $now);
                ContactBookTypePeriod::appendType($child->id, ContactBookTypePeriod::typeByBirthday($child->birthday), $now);
            }
        });

        DB::commit();

        $this->info('Success');
    }
}
