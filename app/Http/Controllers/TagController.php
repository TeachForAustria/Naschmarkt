<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Http\Requests;

class TagController extends Controller
{
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
    public function getMostViewed(Request $request)
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
