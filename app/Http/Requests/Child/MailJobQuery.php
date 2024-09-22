<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class MailJobQuery extends FormRequest {

    public function rules() {
        return [
            'per_page'   => ['nullable', 'numeric'],
        ];
    }
}
