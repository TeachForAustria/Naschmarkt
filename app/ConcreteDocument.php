<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Storage;

class ConcreteDocument extends Model
{
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    public function generateUuid()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function document()
    {
        return $this->belongsTo('App\Document');
    }

    public function writeContent($content)
    {
        Storage::put($this->uuid . '.' . $this->extension, $content);
    }

    public function readContent()
    {
        return Storage::get($this->uuid . '.' . $this->extension);
    }
}
