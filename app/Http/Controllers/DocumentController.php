<?php

namespace App\Http\Controllers;

use App\Document;
use App\DocumentVersion;
use Illuminate\Http\Request;

use App\Http\Requests;

class DocumentController extends Controller
{
    public function getFileDownload($id)
    {
        $document = Document::with('documentVersions')->findOrFail($id);

        // this is a little bit hacky since we assume that no document version has been deleted
        $documentVersion = $document->documentVersions()->where('version', $document->documentVersions()->count() - 1)->firstOrFail();

        // also, we should actually only access the file via the storage module
        return response()->download(storage_path() . '/app/' . $documentVersion->uuid . '.' . $documentVersion->extension, $document->name);
    }
}
