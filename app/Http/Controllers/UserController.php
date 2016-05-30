<?php

namespace App\Http\Controllers;

use App\User;
use Hash;
use Illuminate\Http\Request;

use App\Http\Requests;
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
     * @param $user_id
     * The useres id.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProfileView($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('profile', compact('user', ['']));
    }

    /**
     * Update the user profile.
     * @param $userId int user id
     * @param Request $request Request request object
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function update($userId, Request $request)
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

        $user = User::findOrFail($userId);
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if($request->input('password') !== null && $request->input('password') !== '')
            $user->password = Hash::make($request->input('password'));

        $user->save();

        return redirect('/user/' . $user->id)->with('status', [
            'type' => 'success',
            'content' => 'Die &Auml;nderungen an deinem Profil wurden gespeichert.'
        ]);
    }
}
