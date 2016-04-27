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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;
use ZipArchive;

class SearchController extends Controller{

    public function _construct(){
        $this->middleware('auth');
    }

    public function searchForQuery(Request $request){

        // Save the query string
        $query = $request->input('searchQuery');

        if($query === ""){

            return view('posts', [
                'posts' => Post::with('tags', 'owner')->get()
            ]);

        }else {

            // Split the query string into the tag values
            $tag_values_queried = explode(",", $query);

            // Get all Tags from the database
            $actual_tags = Tag::all();

            // The tags that are found in the db will be saved to this
            $filtered_tags = array();

            // Loop through the values and check if some match
            foreach ($tag_values_queried as $current_tag_value) {

                // loop through all tags that exists in the DB
                foreach ($actual_tags as $actual_tag) {

                    // If entered tag exists in the DB, add it to
                    // the filtered tags array
                    if ($actual_tag->value === $current_tag_value) {
                        array_push($filtered_tags, $actual_tag);
                    }

                }
            }

            // Get all current posts
            $posts_queried = Post::all();

            // The queried posts will be saved here
            $posts = array();

            // Loop through all existing posts
            foreach ($posts_queried as $post) {

                // Set controlling var false
                $display_this_post = false;

                // Loop through all the tags the current post has
                foreach ($post->tags as $current_tag) {

                    // Loop through all the tags the were filtered earlier
                    foreach ($filtered_tags as $filtered_tag) {

                        // Check if there is a match with the current tag
                        // of the post, if there is, then the controlling
                        // variable is set to true, meaning the post will
                        // be displayed
                        if ($current_tag->id === $filtered_tag->id) {
                            $display_this_post = true;
                        }

                    }
                }

                // If the contolling condition is true,
                // the current post will be pushed to the
                // array that is later returned to the page
                if ($display_this_post === true) {
                    array_push($posts, $post);
                }
            }


            //only if checkbox is checked files will be searched
            if (Input::get('fullTextSearch') === 'yes') {
                $full_text_found_documents = array();

                //get all document_versions
                $document_versions = DocumentVersion::all();

                //loop through them
                foreach ($document_versions as $document_version) {

                    //save the extension
                    $extension = $document_version->extension;

                    //valid extensions where read method exists
                    $checkExtension = array('doc', 'docx', 'pdf', 'txt', 'html');

                    if (in_array($extension, $checkExtension)) {

                        //the method called is read_extension (read_doc, read_docx, read_pdf, read_txt, read_html)
                        $read_method  = 'read_' . $extension;

                        try{

                            //match content of file to query
                            if(stripos($this->$read_method($document_version), $query) !== false){
                                //push the document to the found documents
                                array_push($full_text_found_documents, $document_version->document->post);
                            }

                        } catch (\Exception $e){
                            // if Exception occurs, the Document is misformated and it is just not checked.
                        }

                    }

                }

                //merge arrays
                $posts = array_merge($posts, $full_text_found_documents);

                //delete all duplicates
                $posts = array_unique($posts);
            }


            // Return the posts view with the
            // filtered posts as parameter
            return view('posts', [
                'posts' => $posts]);

        }

    }

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

    private function read_pdf($document_version){
        $parser = new \Smalot\PdfParser\Parser();
        return $parser->parseContent($document_version->readContent())->getText();
    }

    private function read_txt($document_version){
        return $document_version->readContent();
    }

    private function read_html($document_version){
        return strip_tags($document_version->readContent());
    }

}