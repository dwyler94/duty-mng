<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkingHourStatusRequest extends FormRequest {

    public function rules() {
        return [
            'enable'             =>  ['required', 'boolean'],
        ];
    }
}
