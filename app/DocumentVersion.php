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

    public function filesize()
    {
        //get filesize
        $filesize = Storage::size($this->uuid . '.' . $this->extension);
        //standard extension is B for Byte
        $extension = 'B';


        //convert filesize to int convert it to string and check it's length
        if(strlen(strval(intval($filesize))) > 3){

            //convert B to KB
            $filesize = $filesize/1024;
            $extension = 'KB';

            //same as above
            if(strlen(strval(intval($filesize))) > 3){
                //convert KB to MB
                $filesize = $filesize/1024;
                $extension = 'MB';

                if(strlen(strval(intval($filesize))) > 3){
                    //convert MB to GB
                    $filesize = $filesize/1024;
                    $extension = 'GB';
                }
            }

        }

        //round double cut to 3 Characters and delete . at the end if it exists
        return rtrim(substr(round($filesize, 2), 0, 3), '.') . $extension;
    }

    public function filesizeBytes()
    {
        return Storage::size($this->uuid . '.' . $this->extension);
    }
}
