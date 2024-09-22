<?php

namespace App\Mail;

use App\Services\QrService;
use App\Models\Child;
use App\Models\MailJobHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    public $child;
    public $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MailJobHistory $mail,  Child $child, $content)
    {
        //
        $this->mail = $mail;
        $this->child = $child;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $res = $this->subject($this->mail->subject);

        if (Str::contains($this->content, '__children_qr__')) {
            $this->content = Str::replace('__children_qr__', '', $this->content);
            $res->attachFromStorage("public/qrs/{$this->child->id}.png");
        }
        $res->text('mail.mail_notification', ['content' => $this->content]);

        for ($i = 1; $i <= 10; $i++)
        {
            $file = 'file' . $i;
            if ($this->mail->$file)
            {
                $res->attachFromStorage($this->mail->$file->path);
            }
        }
        return $res;
    }
}
