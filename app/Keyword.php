<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Keyword models are used for indexing documents and making them available for full text search.
 * @package App
 */
class Keyword extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];

    /**
     * Get the n:m Relationships to Document
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function keywords()
    {
        return $this->belongsToMany('App\Document');
    }
}
