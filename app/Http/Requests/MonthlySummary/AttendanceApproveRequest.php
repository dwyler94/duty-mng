<?php

namespace App\Http\Requests\MonthlySummary;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceApproveRequest extends FormRequest
{
    public function rules() {
        return [
            'user_id'   =>  ['required', 'exists:users,id'],
            'month'     => ['required', 'numeric'],
            'days'      =>  ['array'],
            'days.*'    =>['numeric', 'max:31']
        ];
    }
}
