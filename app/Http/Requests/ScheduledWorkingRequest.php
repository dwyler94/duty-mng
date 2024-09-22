<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduledWorkingRequest extends FormRequest {

    public function rules() {
        return [
            'year_id'   =>  ['required', 'exists:years,id'],
            'schedules' =>  ['array', 'max:12'],
            'schedules.*.days' => ['nullable', 'numeric','min:1', 'max:31'],
            'schedules.*.month'     =>  ['required', 'numeric', 'min:1', 'max:12']
        ];
    }
}
