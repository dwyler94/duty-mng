<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MailJobHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'        =>  $this->id,
            'child_id'  =>  $this->child_id,
            'child_name'    =>  $this->child ? $this->child->name : null,
            'children_class_id' =>  $this->children_class_id,
            'cnt'   =>  $this->cnt,
            'content'   =>  $this->content,
            'created_at'    =>  $this->created_at,
            'files' =>  $this->files,
            'office'    =>  $this->office,
            'office_id' =>  $this->office_id,
            'office_name'   =>  $this->office_name,
            'subject'   =>  $this->subject,
            'type'  =>  $this->type,
            'updated_at'    =>  $this->updated_at,
            'user_id'   =>  $this->user_id,
        ];
    }
}
