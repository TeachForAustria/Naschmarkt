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
use App\Http\Requests;
use App\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller{

    public function _construct(){
        $this->middleware('auth');
    }

    public function searchForQuery(Request $request){

        // Save the query string
        $query = $request->input('searchQuery');

        // Split the query string into the tag values
        $tag_values_queried = explode(",", $query);

        // Get all Tags from the database
        $actual_tags = Tag::all();

        // The tags that are found in the db will be saved to this
        $filtered_tags = array();

        // Loop through the values and check if some match
        foreach($tag_values_queried as $current_tag_value){

            // loop through all tags that exists in the DB
            foreach($actual_tags as $actual_tag){

                // If entered tag exists in the DB, add it to
                // the filtered tags array
                if($actual_tag->value === $current_tag_value){
                    array_push($filtered_tags,$actual_tag);
                }

            }
        }

        $documents = Document::all();
        echo $documents.ob_get_length();

        foreach($documents as $document){
            echo "looping documents";
            foreach($document->tags as $current_tag){
                echo "looping tags";
                foreach($filtered_tags as $filtered_tag){
                    echo "filtering tags";
                    if($current_tag->id === $filtered_tag->id){
                        echo $document;
                    }

                }
            }
        }

       // return redirect('search');

    }
}