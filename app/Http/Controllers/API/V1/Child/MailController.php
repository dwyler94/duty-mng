<?php

namespace App\Http\Controllers\API\V1\Child;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\Child\MailHistoryQuery;
use App\Http\Requests\Child\MailJobQuery;
use App\Http\Requests\Child\MailRequest;
use App\Http\Requests\Child\MailTemplateQuery;
use App\Http\Resources\MailHistoryLightResource;
use App\Services\AttachmentService;
use App\Jobs\MailNotifJob;
use App\Models\MailHistory;
use App\Models\Child;
use App\Models\MailJobHistory;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MailJobHistoryResource;
use App\Services\UtilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class MailController extends BaseController
{
    public function dispatchMail(MailRequest $request, AttachmentService $attachmentService)
    {
        $user = auth()->user();
        $data = $request->validated();
        $mailJobHistory = new MailJobHistory($data);
        $mailJobHistory->user_id = $user->id;
        $mailJobHistory->office_id = $user->office_id;

        for ($i = 1; $i <= 10; $i++)
        {
            if (!$request->has('file_' . $i)) continue;
            $file = $attachmentService->createAttachmentFile($request->file('file_' . $i), $user);
            $file->save();
            $file_id = 'file_id_' . $i;
            $mailJobHistory->$file_id = $file->id;
        }
        $mailJobHistory->save();

        MailNotifJob::dispatch($mailJobHistory);
        return $this->sendResponse();
    }

    public function getMailTemplate(MailTemplateQuery $request, UtilService $utilService)
    {
        $data = $request->validated();
        $type = $data['type'];

        $content = Storage::get('mail_template/' .($type == MailHistory::TYPE_NORMAL ? 'normal' : 'urgent') . '.txt');
        $user = auth()->user();
        $office = $user->office;
        $loginUrl =  Str::of(config('app.url'))->rtrim('/') . '/login';
        $content = $utilService->templateCompile($content, [
            'office_name'   =>  $office->name,
            'office_address'=> $office->address,
            'office_tel'    =>  $office->tel,
            'office_fax'    =>  $office->fax,
            'login_url'     =>  $loginUrl
        ]);

        return $this->sendResponse(['content' => $content]);
    }

    public function listMailJob(MailJobQuery $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        if (empty($data['per_page']))
        {
            $size = 20;
        } else {
            $size = $data['per_page'];
        }
        $mails = MailJobHistory::with('office')
            ->with('file1')
            ->with('file2')
            ->with('file3')
            ->with('file4')
            ->with('file5')
            ->with('file6')
            ->with('file7')
            ->with('file8')
            ->with('file9')
            ->with('file10')
            ->where(['office_id' => $user->office_id])->orderBy('id', 'desc')->paginate($size);
        return $this->sendResponse([
            'data'  =>  MailJobHistoryResource::collection($mails->items()),
            'total' =>  $mails->total(),
            'per_page'  =>  $mails->perPage(),
            'current_page'  =>  $mails->currentPage()
        ]);
    }
    public function sendQr(Child $child)
    {
        $user = auth()->user();
        if($child->canceled_at!=null)
            return abort(422, "Canceled child");
        $content = Storage::get('mail_template/qr.txt');
        $mailJobHistory = new MailJobHistory([
            'subject'   =>  '登降園打刻用QRコードのお知らせ',
            'content'   =>  $content,
            'children_class_id' =>  $child->class_id,
            'child_id'  =>  $child->id,
            'type'      =>  MailHistory::TYPE_QR,
        ]);
        $mailJobHistory->user_id = $user->id;
        $mailJobHistory->office_id = $user->office_id;
        $mailJobHistory->save();
        MailNotifJob::dispatch($mailJobHistory);
        return $this->sendResponse();
    }
    public function getMailHistories(Child $child, Request $request)
    {
        $user = $request->user;
        if (!Gate::forUser($user)->allows('handle-child', $child))
        {
            abort(404, "Bad request");
        }
        return $this->sendResponse(MailHistoryLightResource::collection(MailHistory::where(['child_id' => $child->id])->get()));
    }
}
