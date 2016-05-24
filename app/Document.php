<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id');
    }

    public function documentVersions()
    {
        return $this->hasMany('App\DocumentVersion');
    }

    public function keywords()
    {
        return $this->belongsToMany('App\Keyword');
    }
}
