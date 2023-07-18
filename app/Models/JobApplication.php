<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function file()
    {
        return $this->belongsTo('App\Models\File', 'related_object_id', 'related_object_id');
    }
}
