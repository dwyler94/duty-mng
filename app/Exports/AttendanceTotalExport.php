<?php

namespace App\Exports;

use App\Models\EmploymentStatus;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;

class AttendanceTotalExport implements WithEvents
{
    protected $attendanceTotals;
    protected $title;

    public function __construct($attendanceTotals, $title)
    {
        $this->attendanceTotals = $attendanceTotals;
        $this->title = $title;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                // dd(storage_path('app/excel/work_total.xlsx'));
                $templateFile = new LocalTemporaryFile(storage_path('app/excel/work_total.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $sheet->setCellValue('A1', $this->title);

                $row = 4;
                foreach ($this->attendanceTotals as $attendanceTotal)
                {
                    $user = $attendanceTotal['user'];
                    $sheet->setCellValue('A' . $row, $attendanceTotal['year_value'] . '年' . $attendanceTotal['month_value'] . '月');
                    $sheet->setCellValue('B' . $row, $user->number);
                    $sheet->setCellValue('C' . $row, $user->name);
                    $sheet->setCellValue('D' . $row, $attendanceTotal['working_days']);
                    $sheet->setCellValue('E' . $row, round($attendanceTotal['total_working_hours'] / 60, 2));
                    if ($user->office->is_headquarter && $user->employment_status_id === EmploymentStatus::NORMAL)
                    {
                        $sheet->setCellValue('F' . $row, '-');
                        $sheet->setCellValue('G' . $row, '-');
                        $sheet->setCellValue('H' . $row, $attendanceTotal['total_rest_hours']);
                        $sheet->setCellValue('I' . $row, round(($attendanceTotal['actual_working_hours_weekdays'] + $attendanceTotal['actual_working_hours_saturday']) / 60, 2));
                        $sheet->setCellValue('J' . $row, '-');
                        $sheet->setCellValue('K' . $row, '-');
                        $sheet->setCellValue('L' . $row, '-');
                        $sheet->setCellValue('M' . $row, '-');
                        $sheet->setCellValue('N' . $row, '-');
                        $sheet->setCellValue('O' . $row, '-');
                        $sheet->setCellValue('P' . $row, '-');
                        $sheet->setCellValue('Q' . $row, $attendanceTotal['midnight_overtime']);
                        $sheet->setCellValue('R' . $row, '-');
                        $sheet->setCellValue('S' . $row, '-');
                        $sheet->setCellValue('T' . $row, '-');
                        $sheet->setCellValue('U' . $row, round($attendanceTotal['annual_paid_time'] / 60, 2));
                        $sheet->setCellValue('V' . $row, $attendanceTotal['annual_paid_days'] . '日');
                        $sheet->setCellValue('W' . $row, round($attendanceTotal['special_paid_time'] / 60, 2));
                        $sheet->setCellValue('X' . $row, $attendanceTotal['special_paid_days'] . '日');
                        $sheet->setCellValue('Y' . $row, round($attendanceTotal['special_unpaid_time'] / 60, 2));
                        $sheet->setCellValue('Z' . $row, $attendanceTotal['special_unpaid_days'] . '日');
                        $sheet->setCellValue('AA' . $row, round($attendanceTotal['other_unpaid_time'] / 60, 2));
                        $sheet->setCellValue('AB' . $row, $attendanceTotal['other_unpaid_days'] . '日');
                        $sheet->setCellValue('AC' . $row, $attendanceTotal['absence_days'] . '日');
                    } else if ($user->office->is_headquarter && $user->employment_status_id === EmploymentStatus::NORMAL)
                    {
                        $sheet->setCellValue('F' . $row, $attendanceTotal['behind_time'] . '分');
                        $sheet->setCellValue('G' . $row, $attendanceTotal['leave_early'] . '分');
                        $sheet->setCellValue('H' . $row, $attendanceTotal['total_rest_hours']);
                        $sheet->setCellValue('I' . $row, '-');
                        $sheet->setCellValue('J' . $row, round($attendanceTotal['actual_working_hours_weekdays'] / 60, 2));
                        $sheet->setCellValue('K' . $row, round($attendanceTotal['actual_working_hours_saturday'] / 60, 2));
                        $sheet->setCellValue('L' . $row, '-');
                        $sheet->setCellValue('M' . $row, round($attendanceTotal['overtime_hours_weekdays'] / 60, 2));
                        $sheet->setCellValue('N' . $row, round($attendanceTotal['overtime_hours_saturday'] / 60, 2));
                        $sheet->setCellValue('O' . $row, '-');
                        $sheet->setCellValue('P' . $row, '-');
                        $sheet->setCellValue('Q' . $row, round($attendanceTotal['midnight_overtime'] / 60, 2));
                        $sheet->setCellValue('R' . $row, '-');
                        $sheet->setCellValue('S' . $row, '-');
                        $sheet->setCellValue('T' . $row, round($attendanceTotal['consecutive_working_hours'] / 60, 2));
                        $sheet->setCellValue('U' . $row, round($attendanceTotal['annual_paid_time'] / 60, 2));
                        $sheet->setCellValue('V' . $row, $attendanceTotal['annual_paid_days'] . '日');
                        $sheet->setCellValue('W' . $row, round($attendanceTotal['special_paid_time'] / 60, 2));
                        $sheet->setCellValue('X' . $row, $attendanceTotal['special_paid_days'] . '日');
                        $sheet->setCellValue('Y' . $row, round($attendanceTotal['special_unpaid_time'] / 60, 2));
                        $sheet->setCellValue('Z' . $row, $attendanceTotal['special_unpaid_days'] . '日');
                        $sheet->setCellValue('AA' . $row, round($attendanceTotal['other_unpaid_time'] / 60, 2));
                        $sheet->setCellValue('AB' . $row, $attendanceTotal['other_unpaid_days'] . '日');
                        $sheet->setCellValue('AC' . $row, $attendanceTotal['absence_days'] . '日');
                    } else if (!$user->office->is_headquarter && $user->employment_status_id !== EmploymentStatus::NORMAL)
                    {
                        $sheet->setCellValue('F' . $row, $attendanceTotal['behind_time'] . '分');
                        $sheet->setCellValue('G' . $row, $attendanceTotal['leave_early'] . '分');
                        $sheet->setCellValue('H' . $row, $attendanceTotal['total_rest_hours'] . '');
                        $sheet->setCellValue('I' . $row, '-');
                        $sheet->setCellValue('J' . $row, round($attendanceTotal['actual_working_hours_weekdays'] / 60, 2));
                        $sheet->setCellValue('K' . $row, round($attendanceTotal['actual_working_hours_saturday'] / 60, 2));
                        $sheet->setCellValue('L' . $row, '-');
                        $sheet->setCellValue('M' . $row, round($attendanceTotal['overtime_hours_weekdays'] / 60, 2));
                        $sheet->setCellValue('N' . $row, round($attendanceTotal['overtime_hours_saturday'] / 60, 2));
                        $sheet->setCellValue('O' . $row, '-');
                        $sheet->setCellValue('P' . $row, '-');
                        $sheet->setCellValue('Q' . $row, round($attendanceTotal['midnight_overtime'] / 60, 2));
                        $sheet->setCellValue('R' . $row, '-');
                        $sheet->setCellValue('S' . $row, '-');
                        $sheet->setCellValue('T' . $row, round($attendanceTotal['consecutive_working_hours'] / 60, 2));
                        $sheet->setCellValue('U' . $row, round($attendanceTotal['annual_paid_time'] / 60, 2));
                        $sheet->setCellValue('V' . $row, $attendanceTotal['annual_paid_days'] . '日');
                        $sheet->setCellValue('W' . $row, round($attendanceTotal['special_paid_time'] / 60, 2));
                        $sheet->setCellValue('X' . $row, $attendanceTotal['special_paid_days'] . '日');
                        $sheet->setCellValue('Y' . $row, round($attendanceTotal['special_unpaid_time'] / 60, 2));
                        $sheet->setCellValue('Z' . $row, $attendanceTotal['special_unpaid_days'] . '日');
                        $sheet->setCellValue('AA' . $row, round($attendanceTotal['other_unpaid_time'] / 60, 2));
                        $sheet->setCellValue('AB' . $row, $attendanceTotal['other_unpaid_days'] . '日');
                        $sheet->setCellValue('AC' . $row, $attendanceTotal['absence_days'] . '日');
                    } else if (!$user->office->is_headquarter && $user->employment_status_id === EmploymentStatus::NORMAL)
                    {
                        $sheet->setCellValue('F' . $row, $attendanceTotal['behind_time'] . '分');
                        $sheet->setCellValue('G' . $row, $attendanceTotal['leave_early'] . '分');
                        $sheet->setCellValue('H' . $row, $attendanceTotal['total_rest_hours'] . '');
                        $sheet->setCellValue('I' . $row, round(($attendanceTotal['actual_working_hours_weekdays'] + $attendanceTotal['actual_working_hours_saturday']) / 60, 2));
                        $sheet->setCellValue('J' . $row, '-');
                        $sheet->setCellValue('K' . $row, '-');
                        $sheet->setCellValue('L' . $row, '-');
                        $sheet->setCellValue('M' . $row, '-');
                        $sheet->setCellValue('N' . $row, '-');
                        $sheet->setCellValue('O' . $row, round($attendanceTotal['overtime_hours_non_statutory'] / 60, 2));
                        $sheet->setCellValue('P' . $row, round($attendanceTotal['overtime_hours_statutory'] / 60, 2));
                        $sheet->setCellValue('Q' . $row, round($attendanceTotal['midnight_overtime'] / 60, 2));
                        $sheet->setCellValue('R' . $row, round($attendanceTotal['off_shift_working_hours'] / 60, 2));
                        $sheet->setCellValue('S' . $row, round($attendanceTotal['substitute_holiday_time'] / 60, 2));
                        $sheet->setCellValue('T' . $row, round($attendanceTotal['consecutive_working_hours'] / 60, 2));
                        $sheet->setCellValue('U' . $row, round($attendanceTotal['annual_paid_time'] / 60, 2));
                        $sheet->setCellValue('V' . $row, $attendanceTotal['annual_paid_days'] . '日');
                        $sheet->setCellValue('W' . $row, round($attendanceTotal['special_paid_time'] / 60, 2));
                        $sheet->setCellValue('X' . $row, $attendanceTotal['special_paid_days'] . '日');
                        $sheet->setCellValue('Y' . $row, round($attendanceTotal['special_unpaid_time'] / 60, 2));
                        $sheet->setCellValue('Z' . $row, $attendanceTotal['special_unpaid_days'] . '日');
                        $sheet->setCellValue('AA' . $row, round($attendanceTotal['other_unpaid_time'] / 60, 2));
                        $sheet->setCellValue('AB' . $row, $attendanceTotal['other_unpaid_days'] . '日');
                        $sheet->setCellValue('AC' . $row, $attendanceTotal['absence_days'] . '日');
                    }
                    $row++;
                }
                $sheet->export($event->getConcernable());
                return $sheet;
            }
        ];
    }
}
