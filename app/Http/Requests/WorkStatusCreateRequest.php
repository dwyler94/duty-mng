<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkStatusCreateRequest extends FormRequest {

    public function rules() {
        return [
            'user_id' =>  ['required', 'exists:users,id'],
            'date'      =>  ['required', 'date_format:Y-m-d'],
            'commuting_time_1' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'leave_time_1'     => ['nullable', 'date_format:Y-m-d H:i:s'],
            'commuting_time_2' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'leave_time_2'     => ['nullable', 'date_format:Y-m-d H:i:s']
        ];
    }
}
