<?php

namespace App\Http\Controllers;

use App\ConcreteDocument;
use App\Document;
use App\Tag;
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
            return redirect('upload')->with('error', 'Uploaded file is not valid');
        } else if(!$file->isValid()){
            // sending back with error message.
            return redirect('upload')->with('error', 'Uploaded file is not valid');
        } else {
            //create the document
            $document = new Document();
            $document->name = $request->input('title'); //$file->getClientOriginalName();
            $document->description = $request->input('description');
            $document->owner_id = Auth::user()->id;
            $document->save();

            // create and save concrete document
            $concreteDocument = new ConcreteDocument();
            $concreteDocument->generateUuid();
            $concreteDocument->extension = $file->guessExtension();
            $document->concreteDocuments()->save($concreteDocument);
            $concreteDocument->writeContent(fopen($file->getRealPath(), 'r'));

            // save tags
            foreach(explode(',', $request->input('tags')) as $tag) {
                $tagModel = Tag::firstOrCreate([
                    'value' => $tag
                ]);
                $document->tags()->save($tagModel);
            }

            return redirect('upload');
        }
    }

    public function showPostsView()
    {
        return view('posts', [
            'posts' => Document::with('tags', 'owner')->get()
        ]);
    }
}
