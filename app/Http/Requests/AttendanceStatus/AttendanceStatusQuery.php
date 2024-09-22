<?php

namespace App\Http\Requests\AttendanceStatus;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceStatusQuery extends FormRequest {
    public function rules() {
        return [
            'month'      => ['required', 'numeric', 'min:202001', 'max:204012'],
        ];
    }
}
