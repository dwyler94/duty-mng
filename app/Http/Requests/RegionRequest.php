<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegionRequest extends FormRequest {

    public function rules() {
        return [
            'name'      =>  ['required'],
            'offices'   =>  ['nullable', 'array'],
            'offices.*' =>  ['exists:offices,id'],
        ];
    }
}
