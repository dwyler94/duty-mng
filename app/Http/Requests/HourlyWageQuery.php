<?php

namespace App\Http\Requests;

use App\Models\ApplicationClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class HourlyWageQuery extends FormRequest {
    public function rules() {
        return [
            'office_id'             =>  ['nullable', 'exists:offices,id'],
        ];
    }
}
