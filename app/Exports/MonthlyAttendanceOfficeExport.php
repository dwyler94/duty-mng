<?php

namespace App\Exports;

use App\Models\ChildrenClass;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Services\UtilService;

class MonthlyAttendanceOfficeExport implements WithEvents
{
    CONST START_ROWS = 7;
    CONST END_ROWS = 1920;
    CONST CHILD_ROWS = 6;

    protected $attendanceData;
    protected $office;
    protected $year;
    protected $month;
    protected $holidays;

    public function __construct($office, $attendanceData, $year, $month, $holidays)
    {
        $this->attendanceData = $attendanceData;
        $this->office = $office;
        $this->year = $year;
        $this->month = $month;
        $this->holidays = $holidays;
    }
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {

                $templateFile = new LocalTemporaryFile(storage_path('app/excel/attendance_monthly.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $date = Carbon::create($this->year, $this->month, 1, 0, 0, 0);
                $sheet->setCellValue('B1', (Date::parse($date)->toWareki()->format('Y年m月度')));
                $sheet->setCellValue('F1', $this->office->name . "  登降園簿（月間）");

                for ($i = 1; $i <= 31; $i++) {
                    $carbon = Carbon::create($this->year, $this->month, $i, 0, 0, 0);
                    if ($carbon->format('n') == $this->month) {
                        $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $i).'3', $carbon->format('Y/m/d'));
                        if (array_key_exists($carbon->format('Y-m-d'), $this->holidays)) {
                            $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $i).'4', $this->holidays[$carbon->format('Y-m-d')]);
                        }
                        $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $i).'5', $carbon->format('j'));
                    }
                }

                $row = self::START_ROWS;
                $classCnt = $childCnt = 0;
                foreach ($this->attendanceData as $classId => $classItem) {
                    $class = ChildrenClass::where('id', $classId)->first();
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(1) . $row, $class->name);
                    $e = ($row + (COUNT($classItem) * (self::CHILD_ROWS)) - 1 );
                    $sheet->mergeCells(Coordinate::stringFromColumnIndex(1) . $row . ":" . Coordinate::stringFromColumnIndex(1) . $e);
                    $sheet->getStyle( 'A'.$e.':AJ'.$e )->getBorders()->getBottom()->setBorderStyle( Border::BORDER_DOUBLE );
                    foreach ($classItem as $childItem) {
                        foreach ($childItem as $day => $item) {
                            if ($day == 32) {
                                if(!empty($item['previous_extension'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + 32) . ($row + 3), $item['previous_extension']);
                                }
                                if(!empty($item['extension'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + 32) . ($row + 4), $item['extension']);
                                }
                                if(!empty($item['extension']) || !empty($item['previous_extension'])) {
                                    $totalExtension = (new UtilService)->addTimeToTime($item['extension'] ?? null, $item['previous_extension'] ?? null);
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + 32) . ($row + 5), $totalExtension);
                                }
                            } else {
                                if(!empty($item['name'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(2) . $row, $item['name']);
                                }
                                if(!empty($item['number'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(3) . $row, $item['number']);
                                }
                                if(!empty($item['commuting_time'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $day) . $row, Carbon::parse($item['commuting_time'])->format('H:i'));
                                }
                                if(!empty($item['leave_time'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $day) . ($row + 1), Carbon::parse($item['leave_time'])->format('H:i'));
                                }
                                if(!empty($item['reason_for_absences_plan_ruby'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $day) . ($row + 2), "(予)". $item['reason_for_absences_plan_ruby']);
                                } else if(!empty($item['reason_for_absence_ruby'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $day) . ($row + 2), $item['reason_for_absence_ruby']);
                                }
                                if(!empty($item['previous_extension'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $day) . ($row + 3), Carbon::parse($item['previous_extension'])->format('H:i'));
                                }
                                if(!empty($item['extension'])) {
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $day) . ($row + 4), Carbon::parse($item['extension'])->format('H:i'));
                                }
                                if(!empty($item['extension']) || !empty($item['previous_extension'])) {
                                    $totalExtension = (new UtilService)->addTimeToTime($item['extension'] ?? null, $item['previous_extension'] ?? null);
                                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(4 + $day) . ($row + 5), Carbon::parse($totalExtension)->format('H:i'));
                                }
                            }
                        }
                        $childCnt++;
                        $row = $row + self::CHILD_ROWS;
                    }
                    $classCnt++;
                }

                for($i = self::START_ROWS + ($childCnt * self::CHILD_ROWS); $i <= self::END_ROWS; $i++) {
                    $sheet->getRowDimension($i)->setVisible(false);
                }
                $sheet->getPageSetup()-> setPrintArea("A1:Aj".($row - 1));

                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }
}
