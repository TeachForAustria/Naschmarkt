<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function setTags($tags) {
        $tags = array_map(function($tag) {
            return Tag::firstOrCreate([
                'value' => $tag
            ])->id;
        }, $tags);
        $this->tags()->sync($tags);
    }
}
