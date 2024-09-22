<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;

class MonthlySummaryApprove extends Mailable
{
    use Queueable, SerializesModels;

    protected $officeName;
    protected $year;
    protected $month;
    protected $username;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($officeName, $username, $year, $month)
    {
        //
        $this->officeName = $officeName;
        $this->year = $year;
        $this->month = $month;
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = $this->officeName . ' ' . $this->username . '様　' . $this->year . "年" . $this->month . "月　月間集計承認完了";

        $message = (new MailMessage)
            ->subject($title)
            ->line($this->officeName . 'の月間集計が承認されました。')
            ->line("勤怠管理システムにログインし、勤務集計をご確認下さい。\n（※勤務集計は権限がある方のみ閲覧・帳票出力が可能です）");
        return $this->subject($title)
            ->markdown('notifications::email', $message->data());
    }
}
