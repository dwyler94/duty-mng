<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class ContactBook345HomeRequest extends FormRequest {

    public function rules()
    {
        return [
            'date'              =>  ['required', 'date_format:Y-m-d'],
            'weather'           =>  ['nullable', 'string'],
            'contact_0_home'    =>  ['nullable', 'string'],
            'guardian'          =>  ['nullable', 'string'],
            'temperature_time_std'  =>  ['nullable', 'date_format:H:i'],
            'temperature_std'   =>  ['nullable', 'numeric'],
        ];
    }
}
