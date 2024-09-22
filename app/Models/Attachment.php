<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    public function getNameAttribute() {
        if (!empty($this->attributes['name'])) return $this->attributes['name'];
        if (empty($this->attributes['path'])) return '';
        $segs = explode('/', $this->attributes['path']);
        return $segs[count($segs) - 1];
    }
}
