<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceQuery extends FormRequest {

    public function rules() {
        return [
            'date'          => ['required', 'date_format:Y-m-d'],
            'class'         => ['nullable'],
            'sort'          => ['nullable'],
        ];
    }
}
