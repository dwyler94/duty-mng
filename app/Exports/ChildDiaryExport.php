<?php

namespace App\Exports;

use App\Models\ChildrenClass;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;

class ChildDiaryExport implements WithEvents
{
    protected $childrenClasses;
    protected $office;
    protected $diary;
    protected $childrenClassId;
    protected $stat;

    public function __construct($office, $diary, $childrenClassId, $stat)
    {
        $this->childrenClasses = ChildrenClass::get();
        $this->office = $office;
        $this->diary = $diary;
        $this->childrenClassId = $childrenClassId;
        $this->stat = $stat;
    }


    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app/excel/child_diary.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $sheet->setCellValue('a1', $this->office->name);
                $sheet->setCellValue('a4', $this->getClassLabel($this->childrenClassId));

                $date = Carbon::parse($this->diary->date);
                $date->settings(['formatFunction' => 'translatedFormat']);
                $sheet->setCellValue('f1', $date->format('Y年 F jS（l）'));
                $sheet->setCellValue('f2', '天気：' . $this->diary->weather);

                $sheet->setCellValue('a7', $this->stat['all']);
                $sheet->setCellValue('b7', $this->stat['attend']);
                $sheet->setCellValue('c7', $this->stat['absent']);

                $sheet->setCellValue('e7', $this->diary->reason_for_absence);
                $sheet->setCellValue('h7', $this->diary->special_note);

                $sheet->setCellValue('e12', $this->diary->aim);
                $sheet->setCellValue('h12', $this->diary->activity_content);


                $sheet->setCellValue('e13', $this->diary->consideration);
                $sheet->setCellValue('e14', $this->diary->evaluation_reflection);


                $sheet->setCellValue('a18', $this->diary->health_status);
                $sheet->setCellValue('a24', $this->diary->remarks);

            }
        ];
    }
    public function getClassLabel($classId)
    {
        $cClass = $this->childrenClasses->firstWhere('id', $classId);
        if ($cClass) return $cClass->name;
        return '';
    }

}
