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

        $posts = SearchController::searchForTag($tagToSearch);
        
        return view('posts', [
            'posts' => $posts
        ]);

    }


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