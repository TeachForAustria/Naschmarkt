<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function documents()
    {
        return $this->belongsToMany('App\Document');
    }
}
