<?php

namespace App\Http\Requests\Android;

use Illuminate\Foundation\Http\FormRequest;

class ApiRetryRequest extends FormRequest {

    public function rules() {
        return [
            'device_id' => ['required', 'numeric', 'exists:devices,id'],
            'datetime' => ['required', 'date_format:Y-m-d H:i:s'],
            'retry_items' => ['required', 'array'],
            'retry_items.*' => ['required', 'array'],
            'retry_items.*.datetime' => ['required', 'date_format:Y-m-d H:i:s'],
            'retry_items.*.data' => ['required'],
        ];
    }
}
