<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkingHourQuery extends FormRequest {

    public function rules() {
        return [
            'office_id'           =>  ['nullable', 'exists:offices,id'],
            'employment_status_id'  =>  ['nullable', 'exists:employment_statuses,id']
        ];
    }
}
