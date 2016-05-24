<?php

namespace App\Http\Controllers;

use App\ConcreteDocument;
use App\Document;
use App\DocumentVersion;
use App\Http\Requests;
use App\Post;
use App\Tag;
use DB;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;
use PhpParser\Node\Expr\Array_;
use ZipArchive;

/**
 * Class CloudController
 *
 * Handels the GET Request that origin from the wordcloud
 * page. 
 *
 * @package App\Http\Controllers
 */

class CloudController extends Controller
{

    /**
     * Basic constructor
     */
    public function _construct()
    {
        $this->middleware('auth');
    }

    /**
     * This method is called when a tag inside the wordcloud
     * is clicked by the user. It redirects to the posts page
     * with posts filtered by value of the clicked tag
     *
     * @param Request $request
     * The GET Request by which this method is called
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Returns the posts view with the filtered posts
     */
    public function showTagsView(Request $request)
    {

        // Get the query string from the request
        $query = $request->getQueryString();

        // Exlode the querystring from the GET Request by the '=' character
        $contents = explode("=", $query);

        // Get the first item out of the query string, which equals the
        // tag to search
        $tagToSearch = $contents[1];

        // Utilize the searchForTag method from SearchController to search for
        // posts that have the queried tag
        $posts = SearchController::searchForTag($tagToSearch);

        return view('posts', [
            'posts' => $posts
        ]);

    }

    /**
     * Filters the database for the most viewed tags
     * and saves it into an array
     *
     * @param Request $request
     * The GET Request by which this function is called
     *
     * @return array
     * Returns an array with the most viewed tags, values
     * are sepreated in key value style by ':'
     */
    public function filterMostViewed(Request $request)
    {
        // join post_tag and tags group them by the ID Order by count limit output by 50
        $results = DB::select(DB::raw("SELECT COUNT(*) AS count, t.value FROM post_tag INNER JOIN tags AS t ON post_tag.tag_id = t.id GROUP BY t.id ORDER BY count DESC LIMIT 50"));

        $viewedTags = array();

        //push result to array
        foreach ($results as $result){
            array_push($viewedTags, $result->count . ':' . $result->value);
        }

        return $viewedTags;
    }
}