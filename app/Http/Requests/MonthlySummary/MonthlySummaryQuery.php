<?php

namespace App\Http\Requests\MonthlySummary;

use Illuminate\Foundation\Http\FormRequest;

class MonthlySummaryQuery extends FormRequest {
    public function rules() {
        return [
            'month'     => ['required', 'numeric', 'min:202001', 'max:210012'],
        ];
    }
}
