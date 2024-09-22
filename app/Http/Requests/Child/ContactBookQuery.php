<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class ContactBookQuery extends FormRequest {

    public function rules()
    {
        return [
            'date'              =>  ['required', 'date_format:Y-m-d'],
        ];
    }
}
