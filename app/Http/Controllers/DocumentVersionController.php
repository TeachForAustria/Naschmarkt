<?php

namespace App\Http\Controllers;

use App\DocumentVersion;
use Illuminate\Http\Request;

use App\Http\Requests;

class DocumentVersionController extends Controller
{
    public function uploadFile(Request $request)
    {
        $file = $request->file('file');
        // create and save document version
        $documentVersion = new DocumentVersion();
        $documentVersion->generateUuid();
        $documentVersion->extension = $file->guessExtension();
        $documentVersion->save();
        $documentVersion->writeContent(fopen($file->getRealPath(), 'r'));

        return array(
            'name' => $file->getClientOriginalName(),
            'uuid' => $documentVersion->uuid
        );
    }
}
