<?php

namespace App\Http\Controllers\API\V1\Child;

use App\Http\Controllers\API\V1\BaseController;
use App\Services\AttachmentService;
use App\Models\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends BaseController
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }
    public function upload(Request $request, AttachmentService $attachmentService)
    {
        $user = auth()->user();
        $file = $attachmentService->createAttachmentFile($request->file('file'), $user);
        if (!$file)
        {
            return $this->sendError('Upload failed');
        }
        $file->save();
        return $this->sendResponse($file);
    }
    public function remove(Attachment $attachment)
    {
        $user = auth()->user();
        if ($attachment->user_id !== $user->id)
        {
            abort(403, trans('You are not allowed'));
        }
        $attachment->delete();
        return $this->sendResponse();
    }
}
