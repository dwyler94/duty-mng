<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficeQuery extends FormRequest {
    public function rules() {
        return [
            'region_id'     => ['nullable', 'exists:offices,id'],
            'name'          =>  ['nullable'],
            'page'          =>  ['nullable', 'numeric', 'min:1'],
            'size'          =>  ['nullable', 'numeric', 'min:1']
        ];
    }
}
