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
use ZipArchive;

class CloudController extends Controller
{

    public function _construct()
    {
        $this->middleware('auth');
    }

    public function showTagsView(Request $request){

        // Get the query string from the request
        $query = $request->getQueryString();

        $contents = explode("=",$query);

        $tagToSearch = $contents[1];

        return redirect('search/' . $tagToSearch);

    }

}