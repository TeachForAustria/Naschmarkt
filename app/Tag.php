<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];

    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }
}
