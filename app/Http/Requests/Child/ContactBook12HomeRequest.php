<?php

namespace App\Http\Requests\Child;

use Illuminate\Foundation\Http\FormRequest;

class ContactBook12HomeRequest extends FormRequest {

    public function rules()
    {
        return [
            'date'                  =>  ['required', 'date_format:Y-m-d'],
            'weather'               =>  ['nullable', 'string'],
            'pick_up_person'        =>  ['nullable', 'string'],
            'guardian'          =>  ['nullable', 'string'],
            'temperature_time_std'  =>  ['nullable', 'date_format:H:i'],
            'temperature_std'   =>  ['nullable', 'numeric'],
            'pick_up_time'          =>  ['nullable', 'date_format:H:i'],
            'meal_time_1_home'      =>  ['nullable', 'date_format:H:i'],
            'meal_time_2_home'      =>  ['nullable', 'date_format:H:i'],
            'meal_time_3_home'      =>  ['nullable', 'date_format:H:i'],
            'meal_amount_1_home'    =>  ['nullable', 'numeric'],
            'meal_amount_2_home'    =>  ['nullable', 'numeric'],
            'meal_amount_3_home'    =>  ['nullable', 'numeric'],
            'meal_memo_1_home'      =>  ['nullable', 'string'],
            'meal_memo_2_home'      =>  ['nullable', 'string'],
            'meal_memo_3_home'      =>  ['nullable', 'string'],
            'mood_1_home'           =>  ['nullable', 'in:0,1,2,3'],
            'mood_2_home'           =>  ['nullable', 'in:0,1,2,3'],
            'defecation_1_home'     =>  ['nullable', 'in:0,1,2,3'],
            'defecation_2_home'     =>  ['nullable', 'in:0,1,2,3'],
            'defecation_3_home'     =>  ['nullable', 'in:0,1,2,3'],
//            'defecation_count_1_home' =>  ['nullable', 'numeric'],
//            'defecation_count_2_home' =>  ['nullable', 'numeric'],
            'defecation_time_1_home' =>  ['nullable', 'date_format:H:i'],
            'defecation_time_2_home' =>  ['nullable', 'date_format:H:i'],
            'defecation_time_3_home' =>  ['nullable', 'date_format:H:i'],
            'defecation_memo_1_home' =>  ['nullable', 'string'],
            'defecation_memo_2_home' =>  ['nullable', 'string'],
            'defecation_memo_3_home' =>  ['nullable', 'string'],
            'sleep_start_1_home'    =>  ['nullable', 'date_format:H:i'],
            'sleep_end_1_home'      =>  ['nullable', 'date_format:H:i'],
            'sleep_start_2_home'    =>  ['nullable', 'date_format:H:i'],
            'sleep_end_2_home'      =>  ['nullable', 'date_format:H:i'],
            'bathing_home'          =>  ['nullable', 'in:0,1,2'],
            'temperature_time_1_home' =>  ['nullable', 'date_format:H:i'],
            'temperature_1_home'    =>  ['nullable', 'numeric'],
            'temperature_time_2_home' =>  ['nullable', 'date_format:H:i'],
            'temperature_2_home'    =>  ['nullable', 'numeric'],
            'temperature_time_3_home' =>  ['nullable', 'date_format:H:i'],
            'temperature_3_home'    =>  ['nullable', 'numeric'],
            'state_0_home'          =>  ['nullable', 'string'],
        ];
    }
}
