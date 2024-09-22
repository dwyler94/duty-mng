<?php

namespace App\Http\Requests\Android;

use Illuminate\Foundation\Http\FormRequest;

class ApiStampRequest extends FormRequest {

    public function rules() {
        return [
            'device_id' => ['required', 'numeric', 'exists:devices,id'],
            'datetime' => ['required', 'date_format:Y-m-d H:i:s'],
            'data' => ['required', 'string', 'min:60', 'max:100'],
        ];
    }
}
