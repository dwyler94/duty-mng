<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceDailyStatQuery extends FormRequest {

    public function rules() {
        return [
            'date'              => ['required', 'date_format:Y-m-d'],
            'children_class_id' => ['nullable', 'exists:children_classes,id'],
        ];
    }
}
