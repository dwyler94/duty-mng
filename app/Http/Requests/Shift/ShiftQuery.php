<?php

namespace App\Http\Requests\Shift;

use Illuminate\Foundation\Http\FormRequest;

class ShiftQuery extends FormRequest {
    public function rules() {
        return [
            'month'      => ['required'],
        ];
    }
}
