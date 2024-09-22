<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChildApplicationTableQuery extends FormRequest {
    public function rules() {
        return [
            'month'             =>  ['required', 'date_format:Y-m'],
        ];
    }
}
