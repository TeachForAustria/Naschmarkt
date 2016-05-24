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

/**
 * Controller for Posts
 * This includes the Upload, showing/editing posts and zip download
 *
 * @package App\Http\Controllers
 */
class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show Upload View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUploadView()
    {
        return view('upload');
    }

    /**
     * Create a Post with title, description, owner
     *
     * @param Request $request sent from the frontend
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View list of posts
     */
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

        return view('posts', [
            'posts' => Post::with('tags', 'owner')->get(),
            'status' => array(
                'type' => 'success',
                'content' => 'Der Post wurde erfolgreich angelegt.'
            )
        ]);
    }

    /**
     * Show a list of all Posts
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPostsView()
    {
        return view('posts', [
            'posts' => Post::with('tags', 'owner')->get()
        ]);
    }

    /**
     * Show a specific Post
     *
     * @param $id of the post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Post view
     */
    public function showViewPostView($id)
    {
        $post = Post::with('documents.documentVersions')->findOrFail($id);
        $post->access_count++;
        $post->save();

        return view('posts.view', [
            'post' => $post
        ]);
    }

    /**
     * Show a specific Post to edit
     * Only staff or the owner can edit a post
     *
     * @param $id of the Post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View Edit Post edit view
     */
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

    /**
     * Uodate a specific Post with a title, description, tags and files
     *
     * @param $id of the post
     * @param Request $request containing the new Post information
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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
        return redirect('/posts/' . $id);
    }

    /**
     * Delete a specific Post
     *
     * @param $idToDelete of the post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector to the List of posts view
     * @throws \Exception
     */
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

    /**
     * Downloads all the files from a post into a zip.
     *
     * @param Post $post to download as a zip
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse zip download file
     */
    public function getZipDownload(Post $post){

        $documents = $post->documents;

        //temporary storage
        $filename = storage_path() . '/app/' .'download.zip';

        $zip = new ZipArchive();
        $zip->open($filename, ZipArchive::CREATE);
        foreach ($documents as $document) {

            // this is a little bit hacky since we assume that no document version has been deleted
            $documentVersion = $document->documentVersions()->where('version', $document->documentVersions()->count() - 1)->firstOrFail();

            // add file to zip
            $zip->addFile(storage_path() . '/app/' . $documentVersion->uuid . '.' . $documentVersion->extension, $document->name);
        }

        $zip->close();

        //download the file and delete the file afterwards
        return response()->download($filename, str_replace(array(",", "."), "", str_replace(" ", "-", strtolower($post->name))) . '.zip')->deleteFileAfterSend(true);
    }
}
