<?php

namespace App\Http\Requests\Shift;

use Illuminate\Foundation\Http\FormRequest;

class ChildcareQuery extends FormRequest {
    public function rules() {
        return [
            'date'      => ['required', 'date_format:Y-m-d'],
        ];
    }
}
