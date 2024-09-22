<?php

namespace App\Http\Requests\WorkTotal;

use Illuminate\Foundation\Http\FormRequest;

class IndividualCsvRequest extends FormRequest {
    public function rules() {
        return [
            'user_id'     => ['required', 'exists:users,id'],
            'month'       => ['required', 'numeric', 'min:202001', 'max:210012'],
            'type'        => ['required', 'in:excel,csv']
        ];
    }
}
