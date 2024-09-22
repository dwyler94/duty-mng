<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {

    public function rules() {
        return [
            'name'      =>  ['required', 'string', 'max:50'],
            'number'    =>  ['required', 'string', 'max:50'],
            'employment_status_id' => ['required', 'exists:employment_statuses,id'],
            'enrolled'  =>  ['required', 'boolean'],
            'office_id' =>  ['nullable', 'exists:offices,id'],
            'email'     =>  ['required', 'email'],
            'password'  =>  ['nullable', 'string', 'max:50'],
            'role_id'   =>  ['nullable', 'exists:roles,id'],
            'working_hours'=>   ['nullable', 'numeric', 'min:0', 'max:16'],
            'sort'      =>  ['nullable', 'numeric']
        ];
    }
}
