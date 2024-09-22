<?php

namespace App\Http\Requests\MonthlySummary;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest {
    public function rules() {
        $rules = [
            'user_id'          =>  ['required', 'exists:users,id'],
            'date'             => ['required', 'date_format:Y-m-d'],
            'commuting_time_1' => ['nullable', 'date_format:H:i'],
            'leave_time_1'     => ['nullable', 'date_format:H:i'],
            'commuting_time_2' => ['nullable', 'date_format:H:i'],
            'leave_time_2'     => ['nullable', 'date_format:H:i'],
            'substitute_time'  => ['nullable', 'numeric', 'min:0', 'max:1200'],
            'annual_paid_time' => ['nullable', 'numeric', 'min:0', 'max:1200'],
            'special_paid_time'=> ['nullable', 'numeric', 'min:0', 'max:1200'],
            'special_unpaid_time'=>['nullable', 'numeric', 'min:0', 'max:1200'],
            'other_unpaid_time'=>  ['nullable', 'numeric', 'min:0', 'max:1200'],
            'reason_for_vacation_id'=>  ['nullable', 'exists:reason_for_vacations,id'],
            'remark'            => ['nullable', 'string', 'max:191'],
        ];
        if (!empty($this->substitute_time))
        {
            $rules['substitute_day'] = ['required', 'numeric', 'max:31'];
        }
        return $rules;
    }
}
