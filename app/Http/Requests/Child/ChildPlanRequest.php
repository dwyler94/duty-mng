<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class ChildPlanRequest extends FormRequest {

    public function rules() {
        return [
            'data'                 =>  ['array', 'min:1'],
            'data.*.day_of_weeks'   =>  ['required', 'array'],
            'data.*.day_of_weeks.*' =>  ['required', 'in:1,2,3,4,5,6'],
            'data.*.start_time'    =>  ['required', 'date_format:H:i'],
            'data.*.end_time'      =>  ['required', 'date_format:H:i'],
            'data.*.excluding_holidays'=>  ['nullable', 'boolean'],
        ];
    }
}
