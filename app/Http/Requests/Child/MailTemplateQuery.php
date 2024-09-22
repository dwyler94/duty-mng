<?php

namespace App\Http\Requests\Child;

use App\Models\MailHistory;
use Illuminate\Foundation\Http\FormRequest;

class MailTemplateQuery extends FormRequest {

    public function rules() {
        return [
            'type'          =>  ['required', 'in:' . MailHistory::TYPE_NORMAL . ',' . MailHistory::TYPE_URGENT],
        ];
    }
}
