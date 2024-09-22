<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ChildResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $can_cancel=0;
        if(($this->canceled_at==null)&&(($this->admission_date==null)||($this->admission_date!=null)&&(Carbon::now()->diffInDays(Carbon::createFromFormat('Y-m-d', $this->admission_date),false))>=0)){
            $can_cancel=1;
        }
        return [
            'id'        =>  $this->id,
            'number'    =>  $this->number,
            'name'      =>  $this->name,
            'gender'    =>  $this->gender,
            'birthday'  =>  $this->birthday,
            'admission_date'    =>  $this->admission_date,
            'exit_date' =>  $this->exit_date,
            'email'     =>  $this->email,
            'class_id'  =>  $this->class_id,
            'type'      =>  $this->child_info->type??null,
            'certification_type'    =>  $this->child_info->certification_type??null,
            'company_name'  =>  $this->child_info->company_name??null,
            'free_of_charge'=>  $this->child_info->free_of_charge??null,
            'certificate_of_payment'    =>  $this->child_info->certificate_of_payment??null,
            'certificate_expiration_date'=> $this->child_info->certificate_expiration_date??null,
            'tax_exempt_household'  =>  $this->child_info->tax_exempt_household??null,
            'plan_registered' => $this->plan_registered,
            'remarks'    =>  $this->child_info->remarks??null,
            'canceled_at'   =>  $this->canceled_at,
            'can_cancel'    =>  $can_cancel
        ];
        // return parent::toArray($request);
    }
}
