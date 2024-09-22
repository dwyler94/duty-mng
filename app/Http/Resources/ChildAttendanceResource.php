<?php

namespace App\Http\Resources;

use App\Models\ChildcarePlanDay;
use App\Models\ContactBook;
use App\Services\UtilService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ChildAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $date = request()->get('date');
        $noSchedule = false;

        $reason_for_absence_id = $this->reason_for_absence_id ?? null;
        $contact_status = ContactBook::STATUS_INCOMPLETE;
        if ($date) {
            if (!empty($this->plan_absent_id)) {
                $noSchedule = true;
                $reason_for_absence_id = $this->plan_absent_id;
            }
            $contactBook = ContactBook::whereDate('date', $date)->where(['child_id' => $this->id])->first();
            if ($contactBook)
            {
                $contact_status = $contactBook->status;
            }
        }
        return [
            'id'            =>  $this->id,
            'class_id'      =>  $this->class_id,
            'child_id'      =>  $this->child_id,
            'name'          =>  $this->name,
            'number'  =>  $this->number,
            'day'           =>  $this->day,
            'commuting_time'=>  $this->commuting_time,
            'leave_time'    =>  $this->leave_time,
            'behind_time'   =>  $this->behind_time,
            'leave_early'   =>  $this->leave_early,
            'extension'     =>  $this->extension,
            'previous_extension'     =>  $this->previous_extension,
            'total_extension'     =>  (new UtilService)->addTimeToTime($this->previous_extension, $this->extension),
            'reason_for_absence_id'=>   $reason_for_absence_id,
            'no_schedule'   =>  $noSchedule,
            'contact_status'=> $contact_status,
            'notification_child_id'  =>  $this->notification_child_id,
            'notification_message'  =>  $this->notification_message,
            'process_flag'  =>  $this->process_flag,
        ];
    }
}
