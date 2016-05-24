<?php
/**
 * Created by PhpStorm.
 * User: sons
 * Date: 4/25/16
 * Time: 10:58 AM
 */

namespace App\Http\Controllers;

use App\ConcreteDocument;
use App\Document;
use App\DocumentVersion;
use App\Http\Requests;
use App\Post;
use App\Tag;
use DOMDocument;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/**
 * Class SearchController
 * @package App\Http\Controllers
 */
class SearchController extends Controller{

    public function _construct(){

        $this->middleware('auth');
    }

    /**
     * Search for query. This includes searching for
     * Title, Tags and eventually if selected in all the files
     *
     * @param Request $request sent through a GET request
     * @return View frontend view that is seen by the user
     */
    public function searchForQuery(Request $request){

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
        }

        if($full_query === ''){
            return view('posts', [
                'search_query' => $full_query,
                'posts' => Post::with('tags', 'owner')->orderBy($sort_by, $direction)->get()
            ]);

        }else {

            /*
             * Title Search
             */

            $posts = Post::where('name', 'LIKE', '%' . $full_query . '%')->get();


            /*
             * Tag Search
             */

            //build with the function query for finding the tags
            $posts = $posts->merge($this->searchForTag($full_query));

            /*
             * Full Text Search
             */

            //only if checkbox is checked files will be searched
            if (Input::get('fullTextSearch') === 'yes') {
                //get all document_versions
                $document_versions = DocumentVersion::all();

                //loop through them
                foreach ($document_versions as $document_version) {
                    if(!$posts->contains($document_version->document->post)) {

                        //save the extension
                        $extension = $document_version->extension;

                        //valid extensions where read method exists
                        $checkExtension = array('doc', 'docx', 'pdf', 'txt', 'html');

                        if (in_array($extension, $checkExtension)) {

                            //the method called is read_extension (read_doc, read_docx, read_pdf, read_txt, read_html)
                            $read_method = 'read_' . $extension;

                            try {
                                //match content of file to query
                                if (stripos($this->$read_method($document_version), $full_query) !== false) {
                                    //add the document to the found posts
                                    $posts = $posts->add($document_version->document->post);
                                }

                            } catch (\Exception $e) {
                                // if Exception occurs, the Document is misformatted and won't be checked.
                            }

                        }
                    }
                }
            }

            $posts = $posts->unique();

            if (strcasecmp($direction, 'desc') == 0) {
                $posts = $posts->sortByDesc($sort_by);
            } else {
                $posts = $posts->sortBy($sort_by);
            }

            // Return the posts view with the
            // filtered posts as parameter
            return view('posts', [
                'search_query' => $full_query,
                'posts' => $posts
            ]);

        }

    }

    /**
     * Specific Method for searching tags
     *
     * @param $full_query tag query that are split by ',' to an array of multiple tags
     * @return \Illuminate\Database\Eloquent\Collection|static[] Collection that contains the posts
     */
    public static function searchForTag($full_query){
        return Post::with('tags')->whereHas('tags', function($query) use ($full_query) {
            //select tags where value is in an array with each query
            $query->whereIn('value', explode(",", $full_query));
        })->get();
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