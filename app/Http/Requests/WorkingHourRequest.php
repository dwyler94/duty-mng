<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkingHourRequest extends FormRequest {

    public function rules() {
        return [
            'office_id'             =>  ['required', 'exists:offices,id'],
            'name'                  =>  ['required', 'string', 'max:191'],
            'start_time'            =>  ['required', 'date_format:H:i'],
            'end_time'              =>  ['required', 'date_format:H:i'],
            'employment_status_id'  =>  ['required', 'exists:employment_statuses,id']
        ];
    }
}
