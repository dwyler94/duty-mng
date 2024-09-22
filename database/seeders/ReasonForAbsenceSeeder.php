<?php

namespace Database\Seeders;

use App\Models\ReasonForAbsence;
use Illuminate\Database\Seeder;

class ReasonForAbsenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if (!ReasonForAbsence::where(['id' => ReasonForAbsence::REASON_CORONA])->first()) {
            ReasonForAbsence::create([
                'id'    =>  ReasonForAbsence::REASON_CORONA,
                'name'  =>  'コロナ欠席',
                'ruby'  =>  'コ'
            ]);
        }
        if (!ReasonForAbsence::where(['id' => ReasonForAbsence::REASON_PRIVATE])->first()) {
            ReasonForAbsence::create([
                'id'    =>  ReasonForAbsence::REASON_PRIVATE,
                'name'  =>  '都合欠席',
                'ruby'  =>  '都'
            ]);
        }
        if (!ReasonForAbsence::where(['id' => ReasonForAbsence::REASON_KIBIKI])->first()) {
            ReasonForAbsence::create([
                'id'    =>  ReasonForAbsence::REASON_KIBIKI,
                'name'  =>  '忌引き',
                'ruby'  =>  '忌'
            ]);
        }
        if (!ReasonForAbsence::where(['id' => ReasonForAbsence::REASON_SICK])->first()) {
            ReasonForAbsence::create([
                'id'    =>  ReasonForAbsence::REASON_SICK,
                'name'  =>  '病欠',
                'ruby'  =>  '病'
            ]);
        }
        if (!ReasonForAbsence::where(['id' => ReasonForAbsence::REASON_SUSPENSION])->first()) {
            ReasonForAbsence::create([
                'id'    =>  ReasonForAbsence::REASON_SUSPENSION,
                'name'  =>  '出席停止',
                'ruby'  =>  '出'
            ]);
        }
        if (!ReasonForAbsence::where(['id' => ReasonForAbsence::REASON_VACATION])->first()) {
            ReasonForAbsence::create([
                'id'    =>  ReasonForAbsence::REASON_VACATION,
                'name'  =>  '休園',
                'ruby'  =>  '休'
            ]);
        }
        if (!ReasonForAbsence::where(['id' => ReasonForAbsence::REASON_HOLIDAY])->first()) {
            ReasonForAbsence::create([
                'id'    =>  ReasonForAbsence::REASON_HOLIDAY,
                'name'  =>  '祝日',
                'ruby'  =>  '祝'
            ]);
        }

        if (!ReasonForAbsence::where(['id' => ReasonForAbsence::REASON_DISASTER])->first()) {
            ReasonForAbsence::create([
                'id'    =>  ReasonForAbsence::REASON_DISASTER,
                'name'  =>  '災害',
                'ruby'  =>  '災'
            ]);
        }
    }
}
