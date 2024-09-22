<?php

namespace App\Http\Requests\Child;

use App\Models\MailHistory;
use Illuminate\Foundation\Http\FormRequest;

class MailRequest extends FormRequest {

    public function rules() {
        return [
            'subject'       =>  ['required', 'string'],
            'content'       =>  ['required', 'string'],
            'children_class_id'=>   ['nullable', 'exists:children_classes,id'],
            'child_id'   =>  ['nullable', 'exists:children,id'],
            'type'          =>  ['required', 'in:' . MailHistory::TYPE_NORMAL . ',' . MailHistory::TYPE_URGENT],
            'file_1'        =>  ['nullable'],
            'file_2'        =>  ['nullable'],
            'file_3'        =>  ['nullable'],
            'file_4'        =>  ['nullable'],
            'file_5'        =>  ['nullable'],
            'file_6'        =>  ['nullable'],
            'file_7'        =>  ['nullable'],
            'file_8'        =>  ['nullable'],
            'file_9'        =>  ['nullable'],
            'file_10'       =>  ['nullable'],
        ];
    }
}
