<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show to uploadpage
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload()
    {
        return view('upload');
    }

    /**
     * Show the tagcloud.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cloud()
    {
        return view('cloud');
    }

    /**
     * Show the searchpage.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        return view('search');
    }

    /**
     * Show the users profilepage.
     *
     * @param $user_id
     * The useres id.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile($user_id){
        $user = User::find($user_id);

        return view('profile', compact('user'));
    }
}
