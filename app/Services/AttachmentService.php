<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Support\Str;

class AttachmentService {
    public function createAttachmentFile($file, User $user, $fileName = false)
    {
        if (!$file)
        {
            return null;
        }
        if ($fileName === false)
        {
            $fileName = self::randomFileName($file);
        }
        $fileName = $file->getClientOriginalName();
        $path = Storage::put('public/attachments', $file);
        if (!$path)
        {
            abort(404, 'File upload failed');
        }
        //  $file->storeAs('attachments', $fileName, 'public');
        $url = Storage::url($path);

        $attachment = new Attachment();
        $attachment->path = $path;
        $attachment->url = $url;
        $attachment->upload_user_id = $user->id;
        $attachment->name = $fileName;
        return $attachment;
    }
    public static function randomFileName($file)
    {
        $extension = $file->getClientOriginalExtension();
        $now = new \DateTime();
        $rnd = Str::random(8);
        return $now->getTimestamp() . '_' . $rnd . '.' . ($extension ? $extension : 'dat');
    }
}
