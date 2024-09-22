<?php

namespace App\Http\Requests\WorkTotal;

use App\Models\ApplicationClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class WorkTotalCsvRequest extends FormRequest {
    public function rules() {
        return [
            'start'     => ['required', 'numeric', 'min:202001', 'max:210012'],
            'end'       => ['nullable', 'numeric', 'min:202001', 'max:210012'],
            'retire_included'  => ['nullable', 'boolean'],
            'office_id' =>  ['nullable', 'exists:offices,id']
        ];
    }
}
