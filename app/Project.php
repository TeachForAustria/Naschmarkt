<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function folders()
    {
        return $this->hasMany('App\Folder');
    }
}