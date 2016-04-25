<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Storage;

class DocumentVersion extends Model
{
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
