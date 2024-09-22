<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {

    public function rules() {
        return [
            'number'    => ['required', 'string'],
            'password'  =>  ['required', 'string']
        ];
    }
}
