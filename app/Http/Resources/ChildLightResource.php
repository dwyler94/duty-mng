<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ChildLightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status = 4;
        if($this->canceled_at != null)
            $status = 4;
        else if(($this->admission_date==null)||(Carbon::now()->diffInDays(Carbon::createFromFormat('Y-m-d', $this->admission_date),false))>0)
            $status = 1;
        else if(($this->exit_date==null)||(Carbon::now()->diffInDays(Carbon::createFromFormat('Y-m-d', $this->exit_date),false))>0)
            $status = 2;
        else
            $status = 3;


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
            'canceled_at'   =>  $this->canceled_at,
            'plan_registered' => $this->plan_registered,
            'status'    =>  $status,
        ];
    }
}
