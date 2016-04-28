<?php

namespace App\Http\Controllers;

use App\ConcreteDocument;
use App\Document;
use App\DocumentVersion;
use App\Post;
use App\Tag;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use Ramsey\Uuid\Uuid;
use Storage;
use Validator;

class PostController extends Controller
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
        $allowedExtensions = "pdf,txt,html,docx,doc,zip,jpg,jpeg,png,gif";
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
            $post = new Post();
            $post->name = $request->input('title'); //$file->getClientOriginalName();
            $post->description = $request->input('description');
            $post->owner_id = Auth::user()->id;
            $post->save();

            //create and save document
            $document = new Document();
            $document->name = $file->getClientOriginalName();
            $post->documents()->save($document);


            // create and save concrete document
            $documentVersion = new DocumentVersion();
            $documentVersion->generateUuid();
            $documentVersion->extension = $file->guessExtension();
            $document->documentVersions()->save($documentVersion);
            $documentVersion->writeContent(fopen($file->getRealPath(), 'r'));

            // save tags
            foreach(explode(',', $request->input('tags')) as $tag) {
                $tagModel = Tag::firstOrCreate([
                    'value' => $tag
                ]);
                $post->tags()->save($tagModel);
            }

            return redirect('upload');
        }
    }

    public function showPostsView()
    {
        return view('posts', [
            'posts' => Post::with('tags', 'owner')->get()
        ]);
    }

    public function showViewPostView($id)
    {
        $post = Post::with('documents.documentVersions')->findOrFail($id);
        $post->access_count++;
        $post->save();

        return view('posts.view', [
            'post' => $post
        ]);
    }

    public function deletePost($idToDelete)
    {
        //Find the post with the given id
        $posts = Post::all();
        $postToDelete = null;
        foreach ($posts as $post)
        {
            if ($post->id == $idToDelete)
            {
                $postToDelete = $post;
                break;
            }
        }

        //delete documents of post
        $documents = $postToDelete->documents;
        foreach ($documents as $document)
        {
            //versions
            $docVersions = $document->documentVersions;
            foreach ($docVersions as $docVersion)
            {
                $docVersion->delete();
            }
            $document->delete();
        }

        //delete tags
        $tags = $postToDelete->tags;
        foreach ($tags as $tag)
        {
            $tag->delete();
        }

        $postToDelete->delete();

        return redirect('posts');
    }
}
