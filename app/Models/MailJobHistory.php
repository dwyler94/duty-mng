<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailJobHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'content',
        'children_class_id',
        'child_id',
        'type',
        'cnt'
    ];

    protected $appends = ['office_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mails()
    {
        return $this->hasMany(MailHistory::class);
    }
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
    public function getOfficeNameAttribute()
    {
        if ($this->office) return $this->office->name;
        return '';
    }
    public function file1()
    {
        return $this->belongsTo(Attachment::class, 'file_id_1');
    }
    public function file2()
    {
        return $this->belongsTo(Attachment::class, 'file_id_2');
    }
    public function file3()
    {
        return $this->belongsTo(Attachment::class, 'file_id_3');
    }
    public function file4()
    {
        return $this->belongsTo(Attachment::class, 'file_id_4');
    }
    public function file5()
    {
        return $this->belongsTo(Attachment::class, 'file_id_5');
    }
    public function file6()
    {
        return $this->belongsTo(Attachment::class, 'file_id_6');
    }
    public function file7()
    {
        return $this->belongsTo(Attachment::class, 'file_id_7');
    }
    public function file8()
    {
        return $this->belongsTo(Attachment::class, 'file_id_8');
    }
    public function file9()
    {
        return $this->belongsTo(Attachment::class, 'file_id_9');
    }
    public function file10()
    {
        return $this->belongsTo(Attachment::class, 'file_id_10');
    }
    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function getFilesAttribute() {
        $files = [];
        for ($i = 1; $i < 10; $i++) {
            $key = 'file' . $i;
            if ($this->$key) {
                $files[] = $this->$key;
            }
        }
        return $files;
    }
}
