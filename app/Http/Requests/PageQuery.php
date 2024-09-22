<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageQuery extends FormRequest {
    public function rules() {
        return [
            'size'      => ['required', 'numeric'],
            'page'      =>  ['required', 'numeric']
        ];
    }
}
