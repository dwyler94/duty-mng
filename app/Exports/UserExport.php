<?php

namespace App\Exports;

use App\Constants\CodeGroups;
use App\Models\Office;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Excel;

class UserExport implements WithEvents
{
    protected $users;
    protected $employmentStatuses;
    protected $roles;
    protected $options;

    public function __construct($users, $employmentStatuses, $roles, $options)
    {
        $this->users = $users;
        $this->employmentStatuses = $employmentStatuses;
        $this->roles = $roles;
        $this->options = $options;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {

                $templateFile = new LocalTemporaryFile(storage_path('app/excel/user.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $row = 2;

                foreach ($this->users as $user)
                {
                    $sheet->setCellValue("A" . $row, $user['number']);
                    $sheet->setCellValue("B" . $row, $user['name']);
                    $sheet->setCellValue("C" . $row, $this->getEmploymentStatusLabel($user['employment_status_id']));
                    $sheet->setCellValue("D" . $row, $user['enrolled'] ? '在籍中': '退職');
                    $sheet->setCellValue("E" . $row, !empty($user['office']) ? $user['office']['name'] : '');
                    $sheet->setCellValue("F" . $row, !empty($user['region']) ? $user['region']['name'] : '');
                    $sheet->setCellValue("G" . $row, !empty($user['working_hours']) ? $user['working_hours'] . '時間' : '未定');
                    $sheet->setCellValue("H" . $row, $user['email']);
                    $sheet->setCellValue("I" . $row, $this->getAuthLabel($user['role_id']));
                    $sheet->setCellValue("J" . $row, $this->getOptionLabel(CodeGroups::OVERTIME_PAY,  $user['setting']['overtime_pay']));
                    $sheet->setCellValue("K" . $row, $this->getOptionLabel(CodeGroups::SALARY_DEDUCTION, $user['setting']['salary_deduction']));
                    $sheet->setCellValue("L" . $row, $this->getOptionLabel(CodeGroups::APPLICATION_DEADLINE, $user['setting']['application_deadline']));
                    $row++;
                }
            }
        ];
    }

    private function getEmploymentStatusLabel($employment_status_id)
    {
        $employmentStatus = $this->employmentStatuses->firstWhere('id', $employment_status_id);
        if ($employmentStatus)
        {
            return $employmentStatus->name;
        }
        return '';
    }

    private function getAuthLabel($roleId)
    {
        $role = $this->roles->firstWhere('id', $roleId);
        if ($role)
        {
            return $role->name;
        }
        return '';
    }
    private function getOptionLabel($group, $optionKey)
    {
        if (!$optionKey) return '';
        $option = $this->options->first(function($value, $key) use ($group, $optionKey) {
            return $value->group === $group && $value->key === '' . $optionKey;
        });
        if ($option)
        {
            return $option->value;
        }
        return '';
    }
}
