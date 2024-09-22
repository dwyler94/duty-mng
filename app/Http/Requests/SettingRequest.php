<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest {

    public function rules() {
        return [
            'fraction_commuting_time'   =>  ['required', 'numeric', 'min:0', 'max:60'],
            'fraction_leave_time'   =>  ['required', 'numeric', 'min:0', 'max:60'],
            'fraction_behind_time'  =>  ['required', 'numeric', 'min:0', 'max:60'],
            'fraction_leave_early'  =>  ['required', 'numeric', 'min:0', 'max:60'],
            'round_up_commuting_time'=>  ['required', 'boolean'],
            'truncate_leave_time'   =>  ['required', 'boolean'],
            'round_up_behind_time'  =>  ['required', 'boolean'],
            'truncate_leave_early'  =>  ['required', 'boolean'],
            'consecutive_work'      =>  ['required', 'numeric', 'min:1', 'max:7'],
        ];
    }
}
