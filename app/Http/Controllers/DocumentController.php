<?php

namespace App\Http\Controllers;

use App\ConcreteDocument;
use App\Document;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use Ramsey\Uuid\Uuid;
use Storage;
use Validator;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showUploadView()
    {
        return view('upload');
    }

    public function uploadDocument(Request $request)
    {
        //which formats are allowed
        $allowedExtensions = "pdf,txt,html,docx,zip,jpg,jpeg,png,gif";
        $rules = array('file' => 'required|mimes:'.$allowedExtensions);
        $validator = Validator::make($request->all(), $rules);

        // get file
        $file = $request->file('file');

        //When file does not match allowedExtensions
        if($validator->fails()) {
            return redirect('upload');
        } else if(!$file->isValid()){
            // sending back with error message.
            return redirect('upload')->with('error', 'uploaded file is not valid');
        } else {
            //create the document
            $document = new Document();
            $document->name = $file->getClientOriginalName();
            $document->description = '';
            $document->owner_id = Auth::user()->id;
            $document->save();

            $concreteDocument = new ConcreteDocument();
            $concreteDocument->uuid = Uuid::uuid4();
            $concreteDocument->extension = $file->guessExtension();
            $document->concreteDocuments()->save($concreteDocument);

            Storage::put($concreteDocument->uuid . '.' . $concreteDocument->extension, fopen($file->getRealPath(), 'r'));

            return redirect('upload');
        }
    }
}
