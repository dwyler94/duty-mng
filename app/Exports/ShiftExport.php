<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Excel;


class ShiftExport implements WithEvents
{
    protected $shiftData;
    protected $office;
    protected $year;
    protected $month;

    public function __construct($office, $shiftData, $year, $month)
    {
        $this->shiftData = $shiftData;
        $this->office = $office;
        $this->year = $year;
        $this->month = $month;
    }
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                $cols = ['F', 'G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ'];

                $templateFile = new LocalTemporaryFile(storage_path('app/excel/shift.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                // die("`{$this->year}年{$this->month}月　{$this->office->name}　シフト表` xxx");
                $title = $this->year . "年" . $this->month . "月　" . $this->office->name . "　シフト表";
                // dd($title);
                $sheet->setCellValue('B2', $title);
                $row = 14;
                foreach ($this->shiftData as $shiftItem)
                {
                    $sheet->setCellValue('B' . $row, $shiftItem['name']);
                    $shifts = $shiftItem['shifts'];

                    $work_days = 0;
                    $total_work_minutes = 0;
                    $total_rest_minutes = 0;


                    foreach ($cols as $i => $col)
                    {
                        if (!empty($shifts[$i]))
                        {
                            $shiftNames = [];
                            $is_work_day = false;
                            foreach ($shifts[$i] as $shift)
                            {
                                $name = '';
                                if (!empty($shift->working_hour_name)) {
                                    $name = $shift->working_hour_name;
                                    $is_work_day = true;
                                    $diff = $this->calcPeriod($shift->start_date_time, $shift->end_date_time);
                                    $total_work_minutes += $diff;
                                } else if (!empty($shift->vacation_reason_id)) {
                                    $name = '休';
                                } else {
                                    $name = 'custom';
                                    $is_work_day = true;
                                    $diff = $this->calcPeriod($shift->start_date_time, $shift->end_date_time);
                                    $total_work_minutes += $diff;
                                }
                                $shiftNames[] = $name;

                                $diff = $this->calcPeriod($shift->rest_start_date_time, $shift->rest_end_date_time);
                                $total_rest_minutes += $diff;
                            }
                            if ($is_work_day) $work_days++;
                            $sheet->setCellValue($col . $row, implode(", ", $shiftNames));
                        }
                    }
                    $sheet->setCellValue("AK" . $row, $work_days);
                    $sheet->setCellValue("AM" . $row, round(($total_work_minutes - $total_rest_minutes) / 60));
                    $row++;
                }

                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }
    private function calcPeriod($start, $end){
        if (!$start || !$end) return null;
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        $diff = $start->floatDiffInMinutes($end);
        return $diff;
    }
}
