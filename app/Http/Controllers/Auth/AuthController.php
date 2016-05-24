<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SocialLogin;
use App\User;
use Auth;
use Config;
use Cookie;
use Gate;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Response;
use Mail;
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
        //get input parameter 'provider' from url
        $provider = Input::get('provider');
        //test if valid provider was given (facebook or google)
        if($provider !== 'facebook' && $provider !== 'google')
            abort(400);

        //get input parameter 'type' from url
        $type = Input::get('type');
        //test if valid type was given (connect account or login account)
        if($type !== 'connect' && $type !== 'login')
            abort(400);

        //get config from config/services as services.'provider' (services.facebook or services.google)
        //this returns and array with client_id, client_secret and redirect.
        $config = Config::get('services.' . $provider);

        //change redirect url. in redirect the url has 2 %s's which are exchanged with sprintf(...) to the provider name and type of connection
        $config['redirect'] = sprintf($config['redirect'], $provider, $type);

        //This is the socialite library file SocialiteManager.php.
        //It's a generic way of generating the Driver for any provider.
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
    public function handleProviderCallback(Request $request)
    {
        //get input parameter 'provider' from url
        $provider = Input::get('provider');
        //test if valid provider was given (facebook or google)
        if($provider !== 'facebook' && $provider !== 'google')
            abort(400);

        //get input parameter 'type' from url
        $type = Input::get('type');
        //test if valid type was given (connect account or login account)
        if($type !== 'connect' && $type !== 'login')
            abort(400);

        //same as method above for creating the driver.
        //here we need the created user from it.
        $config = Config::get('services.' . $provider);
        $config['redirect'] = sprintf($config['redirect'], $provider, $type);
        $provider_user = Socialite::buildProvider(
            'Laravel\Socialite\Two\\'. ucfirst($provider).'Provider', $config
        )->user();

        if ($type == 'login') {

            //get SocialLogin from Database.
            $social_login = SocialLogin::where('provider_id', $provider_user->getId())->where('provider', $provider)->first();
            //check if it exists.
            if ($social_login === null) {
                abort(404, 'No associated user not found');
            } else {
                //if it exists login the associated user found
                Auth::login($social_login->user);
                return redirect($this->redirectTo);
            }
        } elseif ($type == 'connect') {
            if(Auth::check()) {
                $user = Auth::user();
            } else {
                echo $request->cookie('activation_token');
                $user = User
                    ::where('activation_token', $request->cookie('activation_token'))
                    ->where('id', $request->cookie('activation_id'))
                    ->firstOrFail();
            }

            //check if account is already connected
            if(SocialLogin::whereUserId($user->id)->whereProvider($provider)->first() !== null){
                abort(400, ucfirst($provider) . " account already connected");
            }

            //create new SocialLogin
            $new_socialLogin = new SocialLogin();

            //set user id from the current user
            $new_socialLogin->user_id = $user->id;
            //set provider id from provider user
            $new_socialLogin->provider_id = $provider_user->getId();
            //set provider string. (google or facebook)
            $new_socialLogin->provider = $provider;

            //save SocialLogin
            $new_socialLogin->save();

            if(!Auth::check()) {
                Auth::login($user);
            }

            return redirect('/user/' . $user->id)->with('status', [
                'type' => 'success',
                'content' => 'Dein Account wurde erfolgreich mit ' . ucfirst($provider) . ' verbunden.'
            ]);
        } else {
            abort(400);
        }

        return '';
    }

    /**
     * disconnect social login from currently logged in user
     *
     * @return Response
     */
    public function disconnectSocialLogin(){

        //get current user
        $user = User::find(Auth::user()->id);
        //delete SocialLogin from given url Parameter
        $user->socialLogins()->where('provider', Input::get('provider'))->delete();

        return redirect('/user/' . $user->id)->with('status', [
            'type' => 'success',
            'content' => 'Die Verlinkung zu ' . ucfirst(Input::get('provider')) . ' wurde entfernt.'
        ]);

        //redirect back user's page
        return redirect('/user/' . $user->id);
    }

    /**
     * Displays the registration form.
     * @return Response
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

        $request->session()->flash('status', [
            'type' => 'success',
            'content' => 'Der Benutzer wurde erfolgreich angelegt.'
        ]);

        return view('auth.register');
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

        return response(view('auth.activate'))
            ->cookie('activation_token', $user->activation_token, 30)
            ->cookie('activation_id', $user->id, 30);
    }

    public function postActivate(Request $request)
    {
        $validator = $this->activationValidator($request->except(['id']));

        if($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        // get the user
        $user = User
            ::where('activation_token', $request->cookie('activation_token'))
            ->where('id', $request->cookie('activation_id'))
            ->firstOrFail();

        $user->password = Hash::make($request->input('password'));
        $user->activation_token = null;
        $user->save();

        Auth::login($user);

        return redirect($this->redirectPath());
    }
}
