<?php

namespace App\Http\Requests;

use App\Models\ApplicationClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ApplicationRequest extends FormRequest {
    public function rules() {
        return [
            'application_class_id' =>  ['required', 'exists:application_classes,id'],
            'attendance_id'        =>  ['nullable', 'exists:attendances,id'],
            'application_date'     =>  ['nullable', 'date_format:Y-m-d'],
            'reason'               =>  ['nullable', 'string'],
            'time_before_correction'=> ['nullable', 'date_format:Y-m-d H:i:s'],
            'time_after_correction'=>  ['nullable', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
