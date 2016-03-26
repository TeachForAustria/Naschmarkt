<?php

namespace App\Http\Controllers\Auth;

use App\SocialLogin;
use Gate;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;


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
        $this->middleware('guest', ['except' => ['logout', 'redirectToProvider','handleProviderCallback']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Redirect the user to the Provider's authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        $provider = Input::get("provider");

        $config = Config::get('services.' . $provider);

        $config['redirect'] = 'http://naschmarkt.com/auth/socialLogin/callback?provider=' . $provider . "&type=" . Input::get("type");

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
        $type = Input::get('type');

        $config = Config::get('services.' . $provider);

        $config['redirect'] = 'http://naschmarkt.com/auth/socialLogin/callback?provider=' . $provider . "&type=" . Input::get("type");

        $provider_user = Socialite::buildProvider(
            'Laravel\Socialite\Two\\'. ucfirst($provider).'Provider', $config
        )->user();

        if ($type == 'login') {

            $social_login = SocialLogin::where('provider_id', $provider_user->getId())->where('provider', $provider)->first();
            if ($social_login === null) {
                abort(404, 'No associated user not found');
            } else {
                Auth::login($social_login->user);
                return view('welcome');
            }
        } elseif ($type == 'connect') {

            $new_socialLogin = new SocialLogin();

            $new_socialLogin->user_id = Auth::user()->id;
            $new_socialLogin->provider_id = $provider_user->getId();
            $new_socialLogin->provider = $provider;

            $new_socialLogin->save();

            return view('welcome');
        }

        return '';
    }

    public function showRegistrationForm()
    {
        if(Gate::denies('register-user')) {
            abort(403);
        }

        parent::showRegistrationForm();
    }

    public function postRegister(Request $request)
    {
        if(Gate::denies('register-user')) {
            abort(403);
        }

        parent::postRegister($request);
    }
}
