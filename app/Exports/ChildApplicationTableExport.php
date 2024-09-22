<?php

namespace App\Exports;

use App\Models\ChildrenClass;
use App\Models\EmploymentStatus;
use App\Models\ReasonForAbsence;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ChildApplicationTableExport implements WithEvents
{
    protected $data;
    protected $office;
    protected $month;
    protected $childrenClasses;

    public function __construct($data, $office, $month)
    {
        $this->data = $data;
        $this->office = $office;
        $this->month = $month;
        $this->childrenClasses = ChildrenClass::get();
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                $office_information = $this->office->getInformationByMonth($this->month);
                $close_time = $office_information->close_time ?? null;

                $templateFile = new LocalTemporaryFile(storage_path('app/excel/child_application_table.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);
                [$yearNumber, $monthNumber] = explode('-', $this->month);
                $sheet->setCellValue('B3', $yearNumber . '年' . ' ' . $monthNumber . '月');
                $sheet->setCellValue('G3', $this->office->name);
                $sheet->setCellValue('U3', $this->office->getCapacityByMonth($this->month) . '名');

                $sheet->setCellValue('E5', $this->data['children_stat'][ChildrenClass::AGE_0]);
                $sheet->setCellValue('E6', $this->data['children_stat'][ChildrenClass::AGE_1]);
                $sheet->setCellValue('E7', $this->data['children_stat'][ChildrenClass::AGE_2]);
                $sheet->setCellValue('E8', $this->data['children_stat'][ChildrenClass::AGE_3]);
                $sheet->setCellValue('E9', $this->data['children_stat'][ChildrenClass::AGE_4]);
                $sheet->setCellValue('E10', $this->data['children_stat'][ChildrenClass::AGE_5]);

                $sheet->setCellValue('N5', $this->data['children_type_stat']['employee_quota']);
                $sheet->setCellValue('N6', $this->data['children_type_stat']['regional']);
                $sheet->setCellValue('N7', $this->data['children_type_stat']['employee_quota_ratio'] / 100);
                $sheet->setCellValue('N9', $this->data['children_type_stat']['regional_ratio'] / 100);
                if($this->data['children_type_stat']['regional_ratio'] > 50 ){
                    $sheet->getDelegate()->getStyle('N9')->getFont()->applyFromArray(['color' => ['rgb' => 'FF0000']]);
                }

                $sheet->getDelegate()->getStyle('B5:B10')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'DDEBF7']]);
                $sheet->getDelegate()->getStyle('K5:K10')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'DDEBF7']]);
                $sheet->getDelegate()->getStyle('AQ5:AT9')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'DDEBF7']]);
                $sheet->getDelegate()->getStyle('B18:AT18')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'FFE699']]);

                if ($close_time)
                {
                    $close_time = Carbon::parse($close_time);
                    $ext_b_label = $close_time->addMinutes(31)->format('H:i') . '～' . $close_time->addMinutes(29)->format('H:i');
                    $ext_c_label = $close_time->addMinutes(1)->format('H:i') . '～' . $close_time->addMinutes(29)->format('H:i');
                    $ext_d_label = $close_time->addMinutes(1)->format('H:i') . '～' . $close_time->addMinutes(29)->format('H:i');
                    $sheet->setCellValue('AT6', $ext_b_label);
                    $sheet->setCellValue('AT7', $ext_c_label);
                    $sheet->setCellValue('AT8', $ext_d_label);
                }


                $baseDay = Carbon::parse($this->month . '-01');
                $daysInMonth = $baseDay->daysInMonth;

                $columns = ['AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD'];

                for ($i = 0; $i < $daysInMonth; $i++)
                {
                    $day = $i + 1;
                    $sheet->setCellValue($columns[$i] . '3', $day);
                    $baseDay->setDay($day);
                    $sheet->setCellValue($columns[$i] . '4', $baseDay->isoFormat('dd'));
                    if($baseDay->isoFormat('dd') == '日'){
                        $sheet->getDelegate()->getStyle($columns[$i] . '4')->getFont()->applyFromArray(['color' => ['rgb' => 'E3342F']]);
                    }elseif ($baseDay->isoFormat('dd') == '土'){
                        $sheet->getDelegate()->getStyle($columns[$i] . '4')->getFont()->applyFromArray(['color' => ['rgb' => '3490DC']]);
                    }
                    $sheet->setCellValue($columns[$i] . '5', $this->zeroToString($this->data['children_stat']['extension_stat'][$day]['a']));
                    $sheet->setCellValue($columns[$i] . '6', $this->zeroToString($this->data['children_stat']['extension_stat'][$day]['b']));
                    $sheet->setCellValue($columns[$i] . '7', $this->zeroToString($this->data['children_stat']['extension_stat'][$day]['c']));
                    $sheet->setCellValue($columns[$i] . '8', $this->zeroToString($this->data['children_stat']['extension_stat'][$day]['d']));
                    $sheet->setCellValue($columns[$i] . '9', $this->zeroToString($this->data['children_stat']['extension_stat'][$day]['e']));


//                    $sheet->setCellValue($columns[$i] . '9', $this->zeroToString($this->data['children_stat']['absent_stat'][$day][ReasonForAbsence::REASON_CORONA]));
//                    $sheet->setCellValue($columns[$i] . '10', $this->zeroToString($this->data['children_stat']['absent_stat'][$day][ReasonForAbsence::REASON_PRIVATE]));
//                    $sheet->setCellValue($columns[$i] . '11', $this->zeroToString($this->data['children_stat']['absent_stat'][$day][ReasonForAbsence::REASON_KIBIKI]));
//                    $sheet->setCellValue($columns[$i] . '12', $this->zeroToString($this->data['children_stat']['absent_stat'][$day][ReasonForAbsence::REASON_SICK]));
//                    $sheet->setCellValue($columns[$i] . '13', $this->zeroToString($this->data['children_stat']['absent_stat'][$day][ReasonForAbsence::REASON_SUSPENSION]));
//                    $sheet->setCellValue($columns[$i] . '14', $this->zeroToString($this->data['children_stat']['absent_stat'][$day][ReasonForAbsence::REASON_VACATION]));
//                    $sheet->setCellValue($columns[$i] . '15', $this->zeroToString($this->data['children_stat']['absent_stat'][$day][ReasonForAbsence::REASON_DISASTER]));

                    $sheet->setCellValue($columns[$i] . '17', $day);
                    $sheet->setCellValue($columns[$i] . '18', $baseDay->isoFormat('dd'));
                }

                $sheet->setCellValue('CE5', '=sum(az5:cd5)');
                $sheet->setCellValue('CE6', '=sum(az6:cd6)');
                $sheet->setCellValue('CE7', '=sum(az7:cd7)');
                $sheet->setCellValue('CE8', '=sum(az8:cd8)');
                $sheet->setCellValue('CE9', '=sum(az9:cd9)');

//                $sheet->setCellValue('CE10', '=sum(az10:cd10)');
//                $sheet->setCellValue('CE11', '=sum(az11:cd11)');
//                $sheet->setCellValue('CE12', '=sum(az12:cd12)');
//                $sheet->setCellValue('CE13', '=sum(az13:cd13)');
//                $sheet->setCellValue('CE14', '=sum(az14:cd14)');
//                $sheet->setCellValue('CE15', '=sum(az15:cd15)');

                $sheet->getDelegate()->getStyle('AZ3:CE4')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'F2F2F2']]);
                $sheet->getDelegate()->getStyle('AZ17:CO18')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'F2F2F2'],]);

                $row = 19;
                foreach ($this->data['children_table'] as $classId => $childClass)
                {
                    $sheet->mergeCells("B$row:AX$row");
                    $sheet->getDelegate()->getStyle("B$row:AX$row")->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'C6E0B4']]);
                    $sheet->getDelegate()->getStyle("B$row:AX$row")->applyFromArray(['font' => ['bold' => true]]);
                    $sheet->setCellValue("B$row", $this->getClassLabel($classId));

                    $sheet->mergeCells("AZ$row:CS$row");
                    $sheet->getDelegate()->getStyle("AZ$row:CS$row")->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'C6E0B4']]);
                    $sheet->getDelegate()->getStyle("AZ$row:CS$row")->applyFromArray(['font' => ['bold' => true]]);

                    $sheet->getDelegate()->getStyle("AZ$row:CS$row")->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => [
                                    'rgb' => '000000'
                                ]
                            ]
                        ]
                    ]);

                    $sheet->setCellValue("AZ$row", $this->getClassLabel($classId));
                    $sheet->getDelegate()->getStyle("B$row:AX$row")->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => [
                                    'rgb' => '000000'
                                ]
                            ]
                        ]
                    ]);

                    $row++;
                    $index = 1;
                    foreach ($childClass as $childItem)
                    {
                        $sheet->setCellValue("B$row", $index);
                        $sheet->mergeCells("C$row:F$row");
                        $sheet->setCellValue("C$row", $this->convertToDateString($childItem['admission_date']));
                        $sheet->mergeCells("G$row:K$row");
                        $sheet->setCellValue("G$row", $childItem['name']);
                        $sheet->setCellValue("L$row", $this->convertToDateString($childItem['birthday']));
                        $sheet->mergeCells("L$row:O$row");
                        $sheet->setCellValue("P$row", $this->getAge($childItem['birthday'], $baseDay));
                        $sheet->mergeCells("P$row:R$row");
                        $sheet->setCellValue("S$row", $childItem['type']);
                        $sheet->mergeCells("S$row:W$row");
                        $sheet->setCellValue("X$row", $childItem['company_name']);
                        $sheet->mergeCells("X$row:AF$row");
                        if($childItem['company_name'] == ''){
                            $sheet->getDelegate()->getStyle("X$row")->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'BBB6B6']]);
                        }
                        $sheet->setCellValue("AG$row", $childItem['free_of_charge']);
                        $sheet->mergeCells("AG$row:AI$row");
                        $sheet->setCellValue("AJ$row", $childItem['certificate_of_payment']);
                        $sheet->mergeCells("AJ$row:AN$row");
                        $sheet->setCellValue("AO$row", $this->convertToDateString($childItem['certificate_expiration_date']));
                        $sheet->mergeCells("AO$row:AS$row");
                        $sheet->setCellValue("AT$row", $childItem['tax_exempt_household']);
                        $sheet->mergeCells("AT$row:AX$row");
                        if($childItem['free_of_charge'] == '対象外'){
                            $sheet->getDelegate()->getStyle("AJ$row:AX$row")->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'BBB6B6']]);
                        }

                        for ($i = 0; $i < $daysInMonth; $i++)
                        {
                            $sheet->setCellValue($columns[$i] . $row, $childItem['extension_state'][$i + 1]);
                        }
                        $sheet->getDelegate()->getStyle("AZ$row:CS$row")->applyFromArray(['borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => [
                                    'rgb' => '000000'
                                ]
                        ]]]);

                        $sheet->getDelegate()->getStyle("CD$row")->applyFromArray(['borders' => [
                            'right' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => '000000'
                                ]
                        ]]]);
                        $sheet->mergeCells("CE$row:CF$row");
                        $sheet->setCellValue("CE$row", $childItem['attend_count']);

                        if (!empty($childItem['absent_state'][ReasonForAbsence::REASON_CORONA])) {
                            $sheet->setCellValue("CG$row", $childItem['absent_state'][ReasonForAbsence::REASON_CORONA]);
                        }
                        if (!empty($childItem['absent_state'][ReasonForAbsence::REASON_PRIVATE])) {
                            $sheet->setCellValue("CH$row", $childItem['absent_state'][ReasonForAbsence::REASON_PRIVATE]);
                        }
                        if (!empty($childItem['absent_state'][ReasonForAbsence::REASON_KIBIKI])) {
                            $sheet->setCellValue("CI$row", $childItem['absent_state'][ReasonForAbsence::REASON_KIBIKI]);
                        }
                        if (!empty($childItem['absent_state'][ReasonForAbsence::REASON_SICK])) {
                            $sheet->setCellValue("CJ$row", $childItem['absent_state'][ReasonForAbsence::REASON_SICK]);
                        }
                        if (!empty($childItem['absent_state'][ReasonForAbsence::REASON_SUSPENSION])) {
                            $sheet->setCellValue("CK$row", $childItem['absent_state'][ReasonForAbsence::REASON_SUSPENSION]);
                        }
                        if (!empty($childItem['absent_state'][ReasonForAbsence::REASON_VACATION])) {
                            $sheet->setCellValue("CL$row", $childItem['absent_state'][ReasonForAbsence::REASON_VACATION]);
                        }
                        if (!empty($childItem['absent_state'][ReasonForAbsence::REASON_DISASTER])) {
                            $sheet->setCellValue("CM$row", $childItem['absent_state'][ReasonForAbsence::REASON_DISASTER]);
                        }
                        $sheet->setCellValue("CN$row", $childItem['exit_date']);
                        $sheet->setCellValue("CO$row", $childItem['regulation_days'] >= 16 ? '●' : '');
                        $sheet->mergeCells("CP$row:CS$row");
                        $sheet->setCellValue("CP$row", $childItem['remarks']);

                        $row++;
                        $index++;
                    }
                }

                $row--;
                $sheet->getDelegate()->getStyle("B19:AX$row")->applyFromArray(['borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [
                            'rgb' => '000000'
                        ]
                ]]]);

            }
        ];
    }
    public function getClassLabel($classId)
    {
        $cClass = $this->childrenClasses->firstWhere('id', $classId);
        if ($cClass) return $cClass->name;
        return '';
    }
    private function zeroToString($value)
    {
        if (!$value) return '';
        return $value;
    }
    private function convertToDateString($date)
    {
        if (!$date) return '';
        return Carbon::parse($date)->format('Y/m/d');
    }

    private function getAge($birthday)
    {
        if (!$birthday) return '';
        $date = Carbon::parse($this->month . '-01');
        if (Carbon::now()->format('Y-m') !== $this->month) {
            $date->lastOfMonth();
        }

        $diffInMonths = Carbon::parse($birthday)->diffInMonths($date);
        $diffInYear = floor($diffInMonths / 12);
        $months = $diffInMonths % 12;

        return $diffInYear . '歳' . $months .'ヶ月';
    }
    private function getTypeLabel()
    {

    }
}
