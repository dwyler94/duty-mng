<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VacationReasonQuery extends FormRequest {

    public function rules() {
        return [
            'name'      =>  ['nullable', 'string', 'max:50']
        ];
    }
}
