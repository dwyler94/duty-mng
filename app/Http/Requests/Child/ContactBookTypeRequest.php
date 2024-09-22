<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ContactBookTypePeriod;

class ContactBookTypeRequest extends FormRequest {

    public function rules()
    {
        return [
            'date' => ['required', 'date_format:Y-m-d'],
            'type' => ['required', Rule::in([ContactBookTypePeriod::TYPE_0, ContactBookTypePeriod::TYPE_1_2, ContactBookTypePeriod::TYPE_3_5])],
        ];
    }

}
