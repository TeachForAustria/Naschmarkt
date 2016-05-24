<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];

    public function keywords()
    {
        return $this->belongsToMany('App\Keyword');
    }
}
