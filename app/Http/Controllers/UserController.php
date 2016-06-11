<?php

namespace App\Http\Controllers;

use App\User;
use Hash;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Validator;


class UserController extends Controller
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
     * Show the users profilepage.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProfileView()
    {
        $user = Auth::user();
        return view('profile', compact('user', ['']));
    }

    /**
     * Update the user profile.
     * @param $userId int user id
     * @param Request $request Request request object
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $updateValidator = Validator::make($request->except(['id']), [
            'password' => 'confirmed|regex:/^(?=.{8,})(?=.*[A-Za-z])(?=.*\d)(?=.*\W).*$/',
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        if($updateValidator->fails()) {
            $this->throwValidationException(
                $request, $updateValidator
            );
        }

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if($request->input('password') !== null && $request->input('password') !== '')
            $user->password = Hash::make($request->input('password'));

        $user->save();

        return redirect('/user')->with('status', [
            'type' => 'success',
            'content' => 'Die &Auml;nderungen an deinem Profil wurden gespeichert.'
        ]);
    }
}
