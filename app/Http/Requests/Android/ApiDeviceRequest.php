<?php

namespace App\Http\Requests\Android;

use Illuminate\Foundation\Http\FormRequest;

class ApiDeviceRequest extends FormRequest {

    public function rules() {
        return [
            'uuid' => ['required', 'uuid'],
            'ver' => ['required', 'numeric'],
            'datetime' => ['required', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
