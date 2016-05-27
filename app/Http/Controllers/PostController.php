<?php

namespace App\Http\Controllers;

use App\Document;
use App\DocumentVersion;
use App\Keyword;
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

            //save the extension
            $extension = $documentVersion->extension;

            //valid extensions where read method exists
            $checkExtension = array('doc', 'docx', 'pdf', 'txt', 'html');

            if (in_array($extension, $checkExtension)) {

                //the method called is read_extension (read_doc, read_docx, read_pdf, read_txt, read_html)
                $read_method = 'read_' . $extension;

                $keywords = explode(' ', $this->$read_method($documentVersion));

                foreach ($keywords as $keyword) {
                    $keyword = preg_replace('/:|_|.|,/', '', $keyword);

                    $keywordModel = Keyword::firstOrCreate([
                        'value' => $keyword
                    ]);

                    if(!$document->keywords()->get()->contains($keywordModel)){
                        $document->keywords()->save($keywordModel);
                    }

                }
            }

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

    /**
     * Show a list of all Posts
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPostsView(Request $request)
    {
        // Save the query string
        $full_query = $request->input('q');

        // Save sort_by string
        $sort_by = $request->input('s');

        // define direction as Ascending
        $direction = 'asc';

        //the sort query can include a, with a new direction
        $sort_by_arr = explode(",", $sort_by);

        if(count($sort_by_arr) > 1){
            $sort_by = $sort_by_arr[0];
            $direction = $sort_by_arr[1];
        }

        // by default created_at is sorted
        if(!isset($sort_by) && !in_array($sort_by, ['name', 'owner_id', 'created_at', 'access_count'])){
            $sort_by = 'created_at';
            $direction = 'desc';
        }

        $posts = Post::with('tags');

        if($full_query !== '') {
            // search in title
            $posts->where('name', 'LIKE', '%' . $full_query . '%');

            // search in tags
            $posts->orWhereHas('tags', function($query) use ($full_query) {
                //select tags where value is in an array with each query
                $query->whereIn('value', explode(",", $full_query));
            });
        }

        if (strcasecmp($direction, 'desc') == 0) {
            $posts->orderBy($sort_by, 'desc');
        } else {
            $posts->orderBy($sort_by, 'asc');
        }

        // pagination
        $posts = $posts->paginate(15);

        // add querystring
        $posts->appends([
            'q' => $full_query,
            's' => $sort_by . ',' . $direction
        ]);

        // Return the posts view with the
        // filtered posts as parameter
        return view('posts', [
            'search_query' => $full_query,
            'posts' => $posts
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

        $request->session()->flash('status', [
            'type' => 'success',
            'content' => 'Deine &Auml;nderungen wurden erfolgreich gespeichert.'
        ]);

        return redirect('/posts/' . $id);
    }

    /**
     * Delete a specific Post
     *
     * @param $idToDelete of the post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector to the List of posts view
     * @throws \Exception
     */
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
        return response()->download($filename, str_replace(array("_", " "), "-", preg_replace('/:/', '', strtolower($post->name))) . '.zip')->deleteFileAfterSend(true);
    }

    /**
     * Method for reading contents of a .doc file
     *
     * @param $document_version with the extension .doc
     * @return mixed|string Content of the file
     */
    private function read_doc($document_version) {
        $lines = explode(chr(0x0D), $document_version->readContent());
        $outtext = "";
        foreach($lines as $line) {
            $pos = strpos($line, chr(0x00));
            if (!(($pos !== FALSE)||(strlen($line)==0))) {
                $outtext .= $line." ";
            }
        }
        $outtext = preg_replace('/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/', '' , $outtext);
        return $outtext;
    }
    /**
     * Method for reading contents of a .docx file
     *
     * @param $document_version with the extension .docx
     * @return mixed|string Content of the file
     */
    private function read_docx($document_version) {
        // Create new ZIP archive
        $zip = new ZipArchive;
        // Open received archive file
        if (true === $zip->open(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $document_version->uuid . '.docx')) {
            // If done, search for the data file in the archive
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                // If found, read it to the string
                $data = $zip->getFromIndex($index);
                // Close archive file
                $zip->close();
                // Load XML from a string
                // Skip errors and warnings
                $xml = new DOMDocument();
                $xml->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                // Return data without XML formatting tags
                return strip_tags($xml->saveXML());
            }
            $zip->close();
        }
        // In case of failure return empty string
        return "";
    }
    /**
     * Method for reading contents of a .pdf file
     *
     * @param $document_version with the extension .pdf
     * @return mixed|string Content of the file
     */
    private function read_pdf($document_version){
        $parser = new \Smalot\PdfParser\Parser();
        return $parser->parseContent($document_version->readContent())->getText();
    }
    /**
     * Method for reading contents of a .txt file
     *
     * @param $document_version with the extension .txt
     * @return mixed|string Content of the file
     */
    private function read_txt($document_version){
        return $document_version->readContent();
    }
    /**
     * Method for reading contents of a .html file
     *
     * @param $document_version with the extension .html
     * @return mixed|string Content of the file
     */
    private function read_html($document_version){
        return strip_tags($document_version->readContent());
    }
}
