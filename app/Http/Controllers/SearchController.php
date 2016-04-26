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
use App\Post;
use App\Tag;
use Illuminate\Http\Request;

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

            // Return the posts view with the
            // filtered posts as parameter
            return view('posts', [
                'posts' => $posts]);

        }

    }
}