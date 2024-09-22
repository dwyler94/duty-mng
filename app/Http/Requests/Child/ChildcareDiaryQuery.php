<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class ChildcareDiaryQuery extends FormRequest {

    public function rules() {
        return [
            'children_class_id' =>  ['required', 'exists:children_classes,id'],
            'date'              =>  ['required', 'date_format:Y-m-d']
        ];
    }
}
