<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkStatusUpdateRequest extends FormRequest {

    public function rules() {
        return [
            'commuting_time_1' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'leave_time_1'     => ['nullable', 'date_format:Y-m-d H:i:s'],
            'commuting_time_2' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'leave_time_2'     => ['nullable', 'date_format:Y-m-d H:i:s']
        ];
    }
}
