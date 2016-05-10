<?php


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
use PhpParser\Node\Expr\Array_;
use ZipArchive;

class CloudController extends Controller
{

    public function _construct()
    {
        $this->middleware('auth');
    }

    public function showTagsView(Request $request)
    {

        // Get the query string from the request
        $query = $request->getQueryString();

        $contents = explode("=", $query);

        $tagToSearch = $contents[1];

        return redirect('search/' . $tagToSearch);

    }


    public function filterMostViewed(Request $request)
    {

        // Get all existing tags
        $tags = Tag::all();

        // Get all existing posts
        $posts = Post::all();

        $viewedTags = array();

        $id = 0;

        // loop through posts with tags and validate them
        // against each other
        foreach ($tags as $tag) {

            $count = 0;
            foreach ($posts as $post) {
                $tagsInPost = $post->tags;

                foreach ($tagsInPost as $ptags) {

                    if ($ptags->value === $tag->value) {
                        $count++;
                    }
                }
            }

            // Save the tags with posts to an array
            if($count > 0){
                $viewedTags[$id] =   $count .':' . $tag->value;
                $id ++;
            }

        }

        // Sort the array
        usort($viewedTags, function($a, $b){
            $valueA = explode(':',$a)[0];
            $valueB = explode(':',$b)[0];

            if($valueA === $valueB){
                return  0;
            }

            return ($valueA < $valueB) ? -1 : 1;

        });

        return $viewedTags;
    }
}