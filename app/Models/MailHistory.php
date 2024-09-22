<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailHistory extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 2;

    const TYPE_NORMAL = 0;
    const TYPE_URGENT = 1;
    const TYPE_QR = 2;

    protected $fillable = [
        'subject', 'content', 'mail_address', 'status', 'children_class_id', 'child_id'
    ];
}
