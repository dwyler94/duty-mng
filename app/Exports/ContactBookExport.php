<?php

namespace App\Exports;

use App\Models\ChildrenClass;
use App\Models\ContactBookTypePeriod;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Excel;

class ContactBookExport implements WithEvents
{
    protected $contactBook;
    protected $office;
    protected $child;
    protected $date;
    protected $contactBookType;

    public function __construct($child, $office, $date, $contactBook, $contactBookType)
    {
        $this->child = $child;
        $this->office = $office;
        $this->contactBook = $contactBook;
        $this->date = $date;
        $this->contactBookType = $contactBookType;
    }
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                if ($this->contactBookType === ContactBookTypePeriod::TYPE_0)
                {
                    $contactType = '0';
                }
                else if ($this->contactBookType === ContactBookTypePeriod::TYPE_1_2)
                {
                    $contactType = '1';
                } else {
                    $contactType = '2';
                }
                $templateFile = new LocalTemporaryFile(storage_path('app/excel/contact_book_' . $contactType . '.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $sheet->setCellValue('b1', $this->office->name);
                $sheet->setCellValue('b4', $this->child->name);
                $sheet->setCellValue('f4', $this->date);
                $sheet->setCellValue('m4', $this->contactBook->weather);


                // $sheet->getDelegate()->getStyle("f12:f12")->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'EBCB42']]);


                if ($contactType === '0')
                {
                    $sheet->setCellValue('g6', $this->contactBook->guardian);
                    $sheet->setCellValue('r6', $this->contactBook->nurse_name);
                    $sheet->setCellValue('e8', $this->moodLabel($this->contactBook->mood));
                    $sheet->setCellValue('e9', $this->timeLabel($this->contactBook->pick_up_time));
                    $sheet->setCellValue('p8', $this->timeLabel($this->contactBook->temperature_time_std));
                    $sheet->setCellValue('u8', $this->contactBook->temperature_std);
                    $sheet->setCellValue('p9', $this->contactBook->pick_up_person);

                    $times = [
                        '1800','1830','1900','1930','2000','2030','2100','2130','2200','2230','2300','2330','2400','2430','0100','0130','0200','0230','0300','0330','0400','0430','0500','0530','0600','0630','0700','0730','0800','0830','0900','0930','1000','1030','1100','1130','1200','1230','1300','1330','1400','1430','1500','1530','1600','1630','1700','1730'
                    ];
                    $row1 = 14;
                    foreach ($times as $time)
                    {
                        if ($time === '2400') {
                            $row1 = 28;
                        }
                        $color = $this->sleepColor($time);
                        if ($color)
                        {
                            $sheet->getDelegate()->getStyle("f" . $row1 . ":" . "f" . $row1)->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => $color]]);
                        }
                        $row1++;
                    }
                    $times = [
                        '18','19','20','21','22','23','24','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17'
                    ];
                    $row = 14;
                    foreach ($times as $time)
                    {
                        if ($time === '24')
                        {
                            $row = 28;
                        }
                        $sheet->setCellValue("H$row", $this->temperatureTimedLabel($time));
                        $sheet->setCellValue("K$row", $this->defecationTimedLabel($time));
                        $sheet->setCellValue("O$row", $this->mealTimedLabel($time));
                        $row += 2;
                    }
                    $date = Carbon::parse($this->date);
                    $date->settings(['formatFunction' => 'translatedFormat']);
                    $sheet->setCellValue('b26', $date->format('m月 d日 (l)'));
                    $date->subDays(1);
                    $sheet->setCellValue('b12', $date->format('m月 d日 (l)'));

                    $sheet->setCellValue('b67', $this->contactBook->state_0_home);
                    $sheet->setCellValue('q67', $this->contactBook->state_0_school);
                    $sheet->setCellValue('b77', $this->contactBook->contact_0_home);
                    $sheet->setCellValue('q77', $this->contactBook->contact_0_school);
                } else if ($contactType === '1') {
                    $sheet->setCellValue('f6', $this->contactBook->guardian);
                    $sheet->setCellValue('o6', $this->contactBook->nurse_name);
                    $sheet->setCellValue('e9', $this->timeLabel($this->contactBook->pick_up_time));
                    $sheet->setCellValue('q9', $this->contactBook->pick_up_person);

                    //========home=========
                    $sheet->setCellValue('c14', $this->timeLabel($this->contactBook->meal_time_1_home));
                    $sheet->setCellValue('f14', $this->mealLabel($this->contactBook->meal_amount_1_home));
                    $sheet->setCellValue('h14', $this->contactBook->meal_memo_1_home);

                    $sheet->setCellValue('c16', $this->timeLabel($this->contactBook->meal_time_2_home));
                    $sheet->setCellValue('f16', $this->mealLabel($this->contactBook->meal_amount_2_home));
                    $sheet->setCellValue('h16', $this->contactBook->meal_memo_2_home);

                    $sheet->setCellValue('c18', $this->timeLabel($this->contactBook->meal_time_3_home));
                    $sheet->setCellValue('f18', $this->mealLabel($this->contactBook->meal_amount_3_home));
                    $sheet->setCellValue('h18', $this->contactBook->meal_memo_3_home);

                    $sheet->setCellValue('f21', $this->moodLabel($this->contactBook->mood_1_home));
                    $sheet->setCellValue('f23', $this->moodLabel($this->contactBook->mood_2_home));

                    $sheet->setCellValue('c26', $this->timeLabel($this->contactBook->defecation_time_1_home));
                    $sheet->setCellValue('f26', $this->defecationLabel($this->contactBook->defecation_1_home));
                    $sheet->setCellValue('h26', $this->contactBook->defecation_memo_1_home);
                    $sheet->setCellValue('c28', $this->timeLabel($this->contactBook->defecation_time_2_home));
                    $sheet->setCellValue('f28', $this->defecationLabel($this->contactBook->defecation_2_home));
                    $sheet->setCellValue('h28', $this->contactBook->defecation_memo_2_home);
                    $sheet->setCellValue('c30', $this->timeLabel($this->contactBook->defecation_time_3_home));
                    $sheet->setCellValue('f30', $this->defecationLabel($this->contactBook->defecation_3_home));
                    $sheet->setCellValue('h30', $this->contactBook->defecation_memo_3_home);


                    $sheet->setCellValue('c33', $this->timePeriodLabel($this->contactBook->sleep_start_1_home, $this->contactBook->sleep_end_1_home));
                    $sheet->setCellValue('c35', $this->timePeriodLabel($this->contactBook->sleep_start_2_home, $this->contactBook->sleep_end_2_home));

                    $sheet->setCellValue('c38', $this->bathLabel($this->contactBook->bathing_home));

                    $sheet->setCellValue('c41', $this->timeLabel($this->contactBook->temperature_time_1_home));
                    $sheet->setCellValue('f41', $this->timeLabel($this->contactBook->temperature_time_2_home));
                    $sheet->setCellValue('j41', $this->timeLabel($this->contactBook->temperature_time_3_home));

                    $sheet->setCellValue('c43', $this->contactBook->temperature_1_home);
                    $sheet->setCellValue('f43', $this->contactBook->temperature_2_home);
                    $sheet->setCellValue('j43', $this->contactBook->temperature_3_home);

                    $sheet->setCellValue('b48', $this->contactBook->state_0_home);


                    //========school=========
                    $sheet->setCellValue('o14', $this->timeLabel($this->contactBook->meal_time_1_school));
                    $sheet->setCellValue('r14', $this->mealLabel($this->contactBook->meal_amount_1_school));
                    $sheet->setCellValue('t14', $this->contactBook->meal_memo_1_school);

                    $sheet->setCellValue('o16', $this->timeLabel($this->contactBook->meal_time_2_school));
                    $sheet->setCellValue('r16', $this->mealLabel($this->contactBook->meal_amount_2_school));
                    $sheet->setCellValue('t16', $this->contactBook->meal_memo_2_school);

                    $sheet->setCellValue('o18', $this->timeLabel($this->contactBook->meal_time_3_school));
                    $sheet->setCellValue('r18', $this->mealLabel($this->contactBook->meal_amount_3_school));
                    $sheet->setCellValue('t18', $this->contactBook->meal_memo_3_school);

                    $sheet->setCellValue('r21', $this->moodLabel($this->contactBook->mood_1_school));
                    $sheet->setCellValue('r23', $this->moodLabel($this->contactBook->mood_2_school));

                    $sheet->setCellValue('o26', $this->timeLabel($this->contactBook->defecation_time_1_school));
                    $sheet->setCellValue('r26', $this->defecationLabel($this->contactBook->defecation_1_school));
                    $sheet->setCellValue('t26', $this->contactBook->defecation_memo_1_school);
                    $sheet->setCellValue('o28', $this->timeLabel($this->contactBook->defecation_time_2_school));
                    $sheet->setCellValue('r28', $this->defecationLabel($this->contactBook->defecation_2_school));
                    $sheet->setCellValue('t28', $this->contactBook->defecation_memo_2_school);
                    $sheet->setCellValue('o30', $this->timeLabel($this->contactBook->defecation_time_3_school));
                    $sheet->setCellValue('r30', $this->defecationLabel($this->contactBook->defecation_3_school));
                    $sheet->setCellValue('t30', $this->contactBook->defecation_memo_3_school);


                    $sheet->setCellValue('o33', $this->timePeriodLabel($this->contactBook->sleep_start_1_school, $this->contactBook->sleep_end_1_school));
                    $sheet->setCellValue('o35', $this->timePeriodLabel($this->contactBook->sleep_start_2_school, $this->contactBook->sleep_end_2_school));

                    $sheet->setCellValue('o38', $this->bathLabel($this->contactBook->bathing_school));

                    $sheet->setCellValue('o41', $this->timeLabel($this->contactBook->temperature_time_1_school));
                    $sheet->setCellValue('r41', $this->timeLabel($this->contactBook->temperature_time_2_school));
                    $sheet->setCellValue('v41', $this->timeLabel($this->contactBook->temperature_time_3_school));

                    $sheet->setCellValue('o43', $this->contactBook->temperature_1_school);
                    $sheet->setCellValue('r43', $this->contactBook->temperature_2_school);
                    $sheet->setCellValue('v43', $this->contactBook->temperature_3_school);

                    $sheet->setCellValue('n48', $this->contactBook->state_0_school);
                } else {
                    $sheet->setCellValue('e6', $this->contactBook->guardian);
                    $sheet->setCellValue('n6', $this->contactBook->nurse_name);
                    $sheet->setCellValue('b11', $this->contactBook->contact_0_home);
                    $sheet->setCellValue('n11', $this->contactBook->contact_0_school);
                }
            }
        ];
    }
    public function moodLabel($mood)
    {
        if ($mood == 1)
        {
            return '普通';
        }
        if ($mood == 2)
        {
            return '良い';
        }
        if ($mood == 3)
        {
            return '悪い';
        }
        return '';
    }
    public function bathLabel($bath)
    {
        if (!$bath) return null;
        if ($bath == 1) return '有り';
        return '無し';
    }
    public function timePeriodLabel($start, $end)
    {
        if (!$start || !$end) return null;
        return Carbon::parse($start)->format('H:i') . ' ~ ' . Carbon::parse($end)->format('H:i');
    }
    public function timeLabel($time)
    {
        if (!$time) return null;
        return Carbon::parse($time)->format('H:i');
    }
    public function sleepColor($time)
    {
        $varSchool = 'sleep_' . $time . '_school';
        $varHome = 'sleep_' . $time . '_home';
        if ($this->contactBook->$varSchool) return 'EBCB42';
        if ($this->contactBook->$varHome) return '8BB3FC';
        return null;
    }
    public function temperatureTimedLabel($time)
    {
        $varSchool = 'temperature_' . $time . '_school';
        $varHome = 'temperature_' . $time . '_home';
        if ($this->contactBook->$varSchool) return $this->contactBook->$varSchool;
        return $this->contactBook->$varHome;
    }
    public function defecationTimedLabel($time)
    {
        $time = (int) $time;
        $varSchool = 'defecation_' . $time . '_school';
        $varHome = 'defecation_' . $time . '_home';
        if ($this->contactBook->$varSchool) return $this->defecationLabel($this->contactBook->$varSchool);
        return $this->defecationLabel($this->contactBook->$varHome);
    }
    public function mealLabel($meal)
    {
        if ($meal == 1) {
            return '完食';
        }
        if ($meal == 2) {
            return '残食';
        }
        if ($meal == 3) {
            return 'おかわり';
        }
        return null;
    }

    public function defecationLabel($defecation)
    {
        if ($defecation == 1)
        {
            return '普';
        }
        if ($defecation == 2)
        {
            return '軟';
        }
        if ($defecation == 3)
        {
            return '固';
        }
        return null;
    }
    public function mealTimedLabel($time)
    {
        $time = (int) $time;
        $varSchool = 'meal_' . $time . '_school';
        $varHome = 'meal_' . $time . '_home';
        if ($this->contactBook->$varSchool) return $this->contactBook->$varSchool;
        return $this->contactBook->$varHome;
    }
}
