<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SocialLogin;
use App\User;
use Gate;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Validator;


/**
 * Type of callback
 * either connect account or login account
 *
 * @var string
 */

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => [
            'logout',
            'showRegistrationForm',
            'postRegister',
            'register',
            'create',
            'redirectToProvider',
            'handleProviderCallback',
            'disconnectSocialLogin',
        ]]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function registrationValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $activationToken = bin2hex(openssl_random_pseudo_bytes(50));

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->is_staff = (isset($data['is_staff']) && $data['is_staff'] == 'yes') ? true : false;
        $user->activation_token = $activationToken;
        $user->save();

        return $user;
    }

    /**
     * Redirect the user to the Provider's authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        $provider = Input::get("provider");
        if($provider !== 'facebook' && $provider !== 'google')
            abort(400);

        $type = Input::get("type");
        if($type !== 'connect' && $type !== 'login')
            abort(400);

        $config = Config::get('services.' . $provider);

        $config['redirect'] = sprintf($config['redirect'], $provider, $type);

        return Socialite::buildProvider(
            'Laravel\Socialite\Two\\'. ucfirst($provider).'Provider', $config
        )->redirect();
    }

    /**
     * Obtain the user information from the Provider.
     * either login in or connect to an existing account
     *
     * @return Response
     */
    public function handleProviderCallback()
    {

        $provider = Input::get('provider');
        if($provider !== 'facebook' && $provider !== 'google')
            abort(400);

        $type = Input::get('type');

        $config = Config::get('services.' . $provider);
        $config['redirect'] = sprintf($config['redirect'], $provider, $type);
        $provider_user = Socialite::buildProvider(
            'Laravel\Socialite\Two\\'. ucfirst($provider).'Provider', $config
        )->user();

        if ($type == 'login') {

            $social_login = SocialLogin::where('provider_id', $provider_user->getId())->where('provider', $provider)->first();
            if ($social_login === null) {
                abort(404, 'No associated user not found');
            } else {
                Auth::login($social_login->user);
                return redirect($this->redirectTo);
            }
        } elseif ($type == 'connect') {

            if(SocialLogin::where('provider_id', $provider_user->getId()) !== null){
                abort(400, ucfirst($provider) . " account already connected");
            }

            $new_socialLogin = new SocialLogin();

            $user = User::find(Auth::user()->id);

            $new_socialLogin->user_id = $user->id;
            $new_socialLogin->provider_id = $provider_user->getId();
            $new_socialLogin->provider = $provider;

            $new_socialLogin->save();

            return redirect('/user/' . $user->id);
        } else {
            abort(400);
        }

        return '';
    }

    /**
     * disconnect social login from currently logged in user
     *
     * @return profile View
     */
    public function disconnectSocialLogin(){

        $user = User::find(Auth::user()->id);
        $user->sociallogins()->where('provider', Input::get('type'))->delete();

        return redirect('/user/' . $user->id);
    }

    /**
     * Displays the registration form.
     * @return View
     */
    public function showRegistrationForm()
    {
        if(Gate::denies('register-user')) {
            abort(403);
        }

        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        if(Gate::denies('register-user')) {
            abort(403);
        }

        $this->register($request);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->registrationValidator($request->all());

        if($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $user = $this->create($request->all());

        Mail::send(
            'auth.emails.register',
            ['token' => $user->activation_token, 'id' => $user->id],
            function ($m) use ($user) {
                $m->from('no-reply@der-naschmarkt.at', 'Der Naschmarkt');

                $m->to($user->email, $user->name)->subject('Naschmarkt Account');
            }
        );

        return redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming activation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function activationValidator(array $data)
    {
        return Validator::make($data, [
            'password' => 'required|confirmed|regex:/^(?=.{8,})(?=.*[A-Za-z])(?=.*\d)(?=.*\W).*$/'
        ]);
    }

    public function showActivationForm(Request $request)
    {
        // check whether the request user exists and has the given token
        $user = User
            ::where('activation_token', $request->input('token'))
            ->where('id', $request->input('id'))
            ->firstOrFail();

        return view('auth.activate', [
            'token' => $user->activation_token,
            'id' => $user->id
        ]);
    }

    public function postActivate(Request $request)
    {
        $validator = $this->activationValidator($request->except(['token', 'id']));

        if($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        // get the user
        $user = User
            ::where('activation_token', $request->input('token'))
            ->where('id', $request->input('id'))
            ->firstOrFail();

        $user->password = Hash::make($request->input('password'));
        $user->activation_token = null;
        $user->save();

        Auth::login($user);

        return redirect($this->redirectPath());
    }
}
