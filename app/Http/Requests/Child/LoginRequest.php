<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {

    public function rules() {
        return [
            'email'     => ['required', 'email:dns,spoof'],
            'password'  => ['required', 'string']
        ];
    }
}
