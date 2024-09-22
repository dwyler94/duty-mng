<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegionMasterQuery extends FormRequest {

    public function rules() {
        return [
            'name'      =>  ['nullable', 'string'],
        ];
    }
}
