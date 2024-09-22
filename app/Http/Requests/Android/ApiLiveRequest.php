<?php

namespace App\Http\Requests\Android;

use Illuminate\Foundation\Http\FormRequest;

class ApiLiveRequest extends FormRequest {

    public function rules() {
        return [
            'uuid' => ['required', 'uuid'],
            'device_id' => ['required', 'numeric', 'exists:devices,id'],
            'latitude' => ['nullable','numeric','regex:/^[-]?((([0-8]?[0-9])(\.[0-9]{6}))|90(\.0{6})?)$/'],
            'longitude' => ['nullable','numeric','regex:/^[-]?(((([1][0-7][0-9])|([0-9]?[0-9]))(\.[0-9]{6}))|180(\.0{6})?)$/'],
            'datetime' => ['required', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
