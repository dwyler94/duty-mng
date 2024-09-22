<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class ChildcareDiaryRequest extends FormRequest {
    public function rules() {
        return [
            'date'     => ['required', 'date_format:Y-m-d'],
            'children_class_id'  => ['required', 'exists:children_classes,id'],
            'weather'   =>  ['nullable', 'string'],
            'reason_for_absence' => ['nullable', 'string'],
            'aim'       =>  ['nullable', 'string'],
            'special_note'       =>  ['nullable', 'string'],
            'activity_content'=> ['nullable', 'string'],
            'consideration' => ['nullable', 'string'],
            'evaluation_reflection' => ['nullable', 'string'],
            'health_status' => ['nullable', 'string'],
            'remarks'   =>  ['nullable', 'string']
        ];
    }
}
