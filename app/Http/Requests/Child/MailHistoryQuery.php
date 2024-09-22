<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class MailHistoryQuery extends FormRequest {

    public function rules()
    {
        return [
            'child_id'              =>  ['nullable', 'exists:children,id'],
        ];
    }
}
