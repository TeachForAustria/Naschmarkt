<?php

namespace App\Http\Controllers;

use App\Document;
use App\DocumentVersion;
use App\Post;
use App\Tag;
use Auth;
use Illuminate\Http\Request;
use Storage;
use App\Http\Requests;
use ZipArchive;

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

        // TODO: file validation
        // get file
        $files = json_decode($request->input('files'), true);

        //create the document
        $post = new Post();
        $post->name = $request->input('title');
        $post->description = $request->input('description');
        $post->owner_id = Auth::user()->id;
        $post->save();

        $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();

        foreach ($files as $file) {
            //create and save document
            $document = new Document();
            $document->name = $file['name'];
            $post->documents()->save($document);

            // create and save document version
            $documentVersion = DocumentVersion::whereUuid($file['uuid'])->firstOrFail();
            if($documentVersion->document_id !== null) {
                // TODO: better error handling
                abort(403);
            } else {
                $documentVersion->document_id = $document->id;
                $documentVersion->save();
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

        $request->session()->flash('status', [
            'type' => 'success',
            'content' => 'Der Post wurde erfolgreich angelegt.'
        ]);

        return view('posts', [
            'posts' => Post::with('tags', 'owner')->get()
        ]);
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
        $post->syncTags(explode(',', $request->input('tags')));
        $post->syncDocuments(json_decode($request->input('files'), true));
        $post->save();

        $request->session()->flash('status', [
            'type' => 'success',
            'content' => 'Deine &Auml;nderungen wurden erfolgreich gespeichert.'
        ]);

        return redirect('/posts/' . $id);
    }

    public function deletePost($idToDelete, Request $request)
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

        $request->session()->flash('status', [
            'type' => 'success',
            'content' => 'Dein Post wurde erfolgreich gel&ouml;scht.'
        ]);

        return redirect('posts');
    }

    public function getZipDownload(Post $post){

        $documents = $post->documents;

        $filename = storage_path() . '/app/' .'download.zip';

        $zip = new ZipArchive();

        $zip->open($filename, ZipArchive::CREATE);

        foreach ($documents as $document) {

            // this is a little bit hacky since we assume that no document version has been deleted
            $documentVersion = $document->documentVersions()->where('version', $document->documentVersions()->count() - 1)->firstOrFail();

            $zip->addFile(storage_path() . '/app/' . $documentVersion->uuid . '.' . $documentVersion->extension, $document->name);
        }

        $zip->close();

        return response()->download($filename, str_replace(array(",", "."), "", str_replace(" ", "-", strtolower($post->name))) . '.zip')->deleteFileAfterSend(true);
    }
}
