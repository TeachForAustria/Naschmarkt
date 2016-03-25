<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function concreteDocuments()
    {
        return $this->hasMany('App\ConcreteDocument');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
}
