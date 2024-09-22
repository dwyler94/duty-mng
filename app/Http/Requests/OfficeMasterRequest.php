<?php

namespace App\Http\Requests;

use App\Models\Office;
use Illuminate\Foundation\Http\FormRequest;

class OfficeMasterRequest extends FormRequest {

    public function rules() {
        return [
            'number'    => ['required', 'string'],
            'name'      =>  ['required', 'string'],
            'type'      =>  ['required', 'in:' . Office::TYPE_NURSERY . ',' . Office::TYPE_OTHERS],
            'rest_deduction_id'=>  ['required', 'exists:rest_deductions,id'],
            'open_time' =>  ['nullable', 'date_format:H:i'],
            'close_time'=>  ['nullable', 'date_format:H:i'],
            'open_time_short' =>  ['nullable'],
            'close_time_short'=>  ['nullable'],
            'capacity'  =>  ['nullable', 'numeric', 'min:0'],
            'appropriate_number_0'  =>  ['nullable', 'numeric', 'min:0'],
            'appropriate_number_1'  =>  ['nullable', 'numeric', 'min:0'],
            'appropriate_number_2'  =>  ['nullable', 'numeric', 'min:0'],
            'appropriate_number_3'  =>  ['nullable', 'numeric', 'min:0'],
            'appropriate_number_4'  =>  ['nullable', 'numeric', 'min:0'],
            'appropriate_number_5'  =>  ['nullable', 'numeric', 'min:0'],
            'business_type_id'      =>  ['nullable', 'exists:business_types,id'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function withValidator($validator)
    {
        $attributeNames = [];
        $data = $validator->getData();
        if (!empty($data['business_type_id']) && ($data['business_type_id'] == 2 || $data['business_type_id'] == 3)) {
            $validator->addRules([
                "open_time_short" => ['required', 'date_format:H:i'],
                "close_time_short" => ['required', 'date_format:H:i'],
            ]);
        }
        $validator->setAttributeNames($attributeNames);
    }
}
