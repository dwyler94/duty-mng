<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class ChildPlanDayRequest extends FormRequest {

    public function rules() {
        return [
            'month'                =>  ['required', 'date_format:Y-m'],
            'data'                 =>  ['array', 'min:1', 'max:31'],
            'data.*.date'          =>  ['required', 'date_format:Y-m-d'],
            'data.*.start_time'    =>  ['nullable', 'date_format:H:i'],
            'data.*.end_time'      =>  ['nullable', 'date_format:H:i'],
            'data.*.absent_id'        =>  ['nullable', 'exists:reason_for_absences,id'],
        ];
    }
}
