<?php

namespace App\Jobs;

use App\Services\QrService;
use App\Services\UtilService;
use App\Mail\MailNotification;
use App\Models\Child;
use App\Models\MailHistory;
use App\Models\MailJobHistory;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class MailNotifJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mailJobHistory;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MailJobHistory $mailJobHistory) {
        //
        $this->mailJobHistory = $mailJobHistory;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(QrService $qrService, UtilService $utilService)
    {
        $children_class_id = $this->mailJobHistory->children_class_id;
        $office_id = $this->mailJobHistory->office_id;
        $subject = $this->mailJobHistory->subject;
        //
        $qb = Child::where(['office_id' => $office_id])
            ->where(function ($query) {
                $query->whereNull('exit_date')
                    ->orWhere('exit_date', '>', Carbon::now());
            });
        if ($children_class_id)
        {
            $qb->where(['class_id' => $children_class_id]);
        }
        if ($this->mailJobHistory->child_id)
        {
            $qb->where(['id' => $this->mailJobHistory->child_id]);
        } else {
            $qb->where(function($query) {
                $query->where('admission_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->orWhere('admission_date', '=', null);
            });
        }

        $children = $qb->get();
        foreach ($children as $child)
        {
            if (!$child->email)
            {
                continue;
            }
            $loginUrl =  Str::of(config('app.url'))->rtrim('/') . '/login';
            $qrService->getChildQrImageTag($child);
            $content = $utilService->templateCompile($this->mailJobHistory->content, [
                // 'children_qr'   =>  '',
                'password'      =>  Crypt::decryptString($child->password),
                'login_id'       =>  $child->email,
                'child_name'    =>  $child->name,
                'office_name'   =>  $child->office->name,
                'office_address'=> $child->office->address,
                'office_tel'    =>  $child->office->tel,
                'office_fax'    =>  $child->office->fax,
                'login_url'     =>  $loginUrl
            ]);

            $mailHistory = MailHistory::create([
                'mail_address'  =>  $child->email,
                'status'        =>  MailHistory::STATUS_PENDING,
                'subject'       =>  $subject,
                'content'       =>  $content,
                'children_class_id'=> $children_class_id,
                'child_id'      =>  $child->id
            ]);
            $this->mailJobHistory->mails()->save($mailHistory);
            try {
                Mail::to($child->email)->send(new MailNotification($this->mailJobHistory, $child, $content));
                $mailHistory->status = MailHistory::STATUS_SUCCESS;
                $this->mailJobHistory->cnt++;
                $this->mailJobHistory->save();
            } catch (Exception $ex)
            {
                Log::info($ex->getMessage());
                $mailHistory->status = MailHistory::STATUS_FAILED;
            }
            $mailHistory->save();
        }
    }

}
