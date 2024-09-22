<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceMonthlyQuery extends FormRequest {

    public function rules() {
        return [
            'month'          => ['required', 'date_format:Y-m'],
        ];
    }
}
