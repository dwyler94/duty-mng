<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class AttendanceRequest extends FormRequest {

    public function rules() {
        return [
            'date'          => ['required', 'date_format:Y-m-d'],
            'commuting_time'=> ['nullable', 'date_format:H:i'],
            'leave_time'    => ['nullable', 'date_format:H:i'],
            'reason_for_absence_id' => ['nullable', 'exists:reason_for_absences,id'],
            'behind_time'   => ['nullable', 'numeric'],
            'extension'     => ['nullable', 'date_format:H:i'],
            'previous_extension'     => ['nullable', 'date_format:H:i'],
        ];
    }
    
}
