<?php

namespace App\Http\Requests;

use App\Constants\CodeGroups;
use App\Models\Code;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class UserSettingRequest extends FormRequest {

    protected $overtimePayRule;
    protected $salaryDeductionRule;
    protected $applicationDeadlineRule;

    public function __construct()
    {
        $codes = Code::whereIn('group', [CodeGroups::OVERTIME_PAY, CodeGroups::SALARY_DEDUCTION, CodeGroups::APPLICATION_DEADLINE])->get()->toArray();
        $overtimePays = Arr::where($codes, function($value) {
            return $value['group'] === CodeGroups::OVERTIME_PAY;
        });
        $salaryDeductions = Arr::where($codes, function($value) {
            return $value['group'] === CodeGroups::SALARY_DEDUCTION;
        });
        $applicationDeadlines = Arr::where($codes, function($value) {
            return $value['group'] === CodeGroups::APPLICATION_DEADLINE;
        });

        $this->overtimePayRule = implode(',', Arr::pluck($overtimePays, 'key'));
        $this->salaryDeductionRule = implode(',', Arr::pluck($salaryDeductions, 'key'));
        $this->applicationDeadlineRule = implode(',', Arr::pluck($applicationDeadlines, 'key'));
    }

    public function rules() {
        return [
            'overtime_pay'   =>  ['nullable', 'in:' . $this->overtimePayRule],
            'salary_deduction'   =>  ['nullable', 'in:' . $this->salaryDeductionRule],
            'application_deadline'  =>  ['nullable', 'in:' . $this->applicationDeadlineRule],
        ];
    }
}
