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

    public function uploadPost(Request $request)
    {

        // get file
        $files = $request->file('files');


        $validFileCount = 0;

        foreach ($files as $file) {

            //which formats are allowed

            //TODO fix validation

            /*
            $allowedExtensions = "pdf,txt,html,docx,doc,zip,jpg,jpeg,png,gif";
            $rules = array('files' => 'required|mimes:'.$allowedExtensions);


            $validator = Validator::make($request->all(), $rules);
            */

            if(isset($file) && $file->isValid()/* && !$validator->fails()*/) {
                $validFileCount++;
            } else {
                // sending back with error message.
                return redirect('upload')->with('error', 'Uploaded file is not valid');
            }
        }

        if($validFileCount === 0) {
            return redirect('upload')->with('error', 'At least one file must be provided.');
        }


        //create the document
        $post = new Post();
        $post->name = $request->input('title');
        $post->description = $request->input('description');
        $post->owner_id = Auth::user()->id;
        $post->save();


        foreach ($files as $file) {


            if(isset($file)) {

                //create and save document
                $document = new Document();
                $document->name = $file->getClientOriginalName();
                $post->documents()->save($document);


                // create and save document version
                $documentVersion = new DocumentVersion();
                $documentVersion->generateUuid();
                $documentVersion->extension = $file->guessExtension();
                $document->documentVersions()->save($documentVersion);
                $documentVersion->writeContent(fopen($file->getRealPath(), 'r'));

            }
        }


        // add tags from checkboxes
        $tagsFromCB = "";
        if($request->get('givenTags') != null) {
            foreach ($request->get('givenTags') as $givenTag) {
                $tagsFromCB = $tagsFromCB . $givenTag . ',';
            }
        }

        // save tags
        foreach(explode(',', $tagsFromCB . ($request->input('tags'))) as $tag) {
            $tagModel = Tag::firstOrCreate([
                'value' => $tag
            ]);
            $post->tags()->save($tagModel);
        }

        return view('posts', [
            'posts' => Post::with('tags', 'owner')->get()
        ]);
        //}
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

    public function showEditPostView($id)
    {
        $post = Post::with('documents.documentVersions')->findOrFail($id);

        if(!(Auth::user()->name == $post->owner->name or Auth::user()->is_staff)) {
            abort(403);
        }

        return view('posts.edit', [
            'post' => $post
        ]);
    }

    public function update($id, Request $request)
    {
        $post = Post::findOrFail($id);

        if(!(Auth::user()->name == $post->owner->name or Auth::user()->is_staff)) {
            abort(403);
        }

        # TODO: validation
        $post->name = $request->input('title');
        $post->description = $request->input('description');
        $post->setTags(explode(',', $request->input('tags')));
        $post->save();
        return redirect('/posts/' . $id);
    }

    public function deletePost($idToDelete)
    {
        //Find the post with the given id
        $postToDelete = Post::findOrFail($idToDelete);

        if(!(Auth::user()->name == $postToDelete->owner->name or Auth::user()->is_staff)) {
            abort(403);
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

        $postToDelete->delete();

        return redirect('posts');
    }
}
