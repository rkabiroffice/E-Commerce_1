<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use App\Models\User;
use App\Models\Customer;
use App\Models\Cart;
use App\Services\SocialRevoke;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private function socialite()
    {
        $socialiteClass = 'Laravel\\Socialite\\Facades\\Socialite';
        return new $socialiteClass;
    }

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a
    | trait to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;


    /**
    * Redirect the user to the Google authentication page.
    */
    public function redirectToProvider(string $provider)
    {
        if (request()->get('query') == 'mobile_app') {
            request()->session()->put('login_from', 'mobile_app');
        }
        if ($provider == 'apple') {
            return $this->socialite()->driver("sign-in-with-apple")
                ->scopes(["name", "email"])
                ->redirect();
        }
        return $this->socialite()->driver($provider)->redirect();
    }

    public function handleAppleCallback(Request $request)
    {
        try {
            $user = $this->socialite()->driver("sign-in-with-apple")->user();
        } catch (\Exception $e) {
            flash("Something Went wrong. Please try again.")->error();
            return redirect()->route('user.login');
        }
        //check if provider_id exist
        $existingUserByProviderId = User::where('provider_id', $user->id)->first();

        if ($existingUserByProviderId) {
            $existingUserByProviderId->access_token = $user->token;
            $existingUserByProviderId->refresh_token = $user->refreshToken;
            if (!isset($user->user['is_private_email'])) {
                $existingUserByProviderId->email = $user->email;
            }
            $existingUserByProviderId->save();
            //proceed to login
            Auth::login($existingUserByProviderId, true);
        } else {
            $existing_or_new_user = User::firstOrNew([
                'email' => $user->email
            ]);
            $existing_or_new_user->provider_id = $user->id;
            $existing_or_new_user->access_token = $user->token;
            $existing_or_new_user->refresh_token = $user->refreshToken;
            $existing_or_new_user->provider = 'apple';
            if (!$existing_or_new_user->exists) {
                $existing_or_new_user->name = 'Apple User';
                if ($user->name) {
                    $existing_or_new_user->name = $user->name;
                }
                $existing_or_new_user->email = $user->email;
                $existing_or_new_user->email_verified_at = date('Y-m-d H:m:s');
            }
            $existing_or_new_user->save();

            Auth::login($existing_or_new_user, true);
        }

        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => Auth::id(),
                    'temp_user_id' => null
                ]);

            Session::forget('temp_user_id');
        }

        if (session('link') != null) {
            return redirect(session('link'));
        }

        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.dashboard');
        }

        return redirect()->route('dashboard');
    }
    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, string $provider)
    {
        if (session('login_from') == 'mobile_app') {
            return $this->mobileHandleProviderCallback($request, $provider);
        }

        try {
            $user = $provider == 'twitter'
                ? $this->socialite()->driver('twitter')->user()
                : $this->socialite()->driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            flash("Something Went wrong. Please try again.")->error();
            return redirect()->route('user.login');
        }

        $existingUserByProviderId = User::where('provider_id', $user->id)->first();
        if ($existingUserByProviderId) {
            $existingUserByProviderId->access_token = $user->token;
            $existingUserByProviderId->save();
            Auth::login($existingUserByProviderId, true);
        } else {
            $existingUser = User::where('email', '!=', null)->where('email', $user->email)->first();
            if ($existingUser) {
                $existingUser->provider_id = $user->id;
                $existingUser->provider = $provider;
                $existingUser->access_token = $user->token;
                $existingUser->save();
                Auth::login($existingUser, true);
            } else {
                $newUser = new User;
                $newUser->name = $user->name;
                $newUser->email = $user->email;
                $newUser->email_verified_at = date('Y-m-d Hms');
                $newUser->provider_id = $user->id;
                $newUser->provider = $provider;
                $newUser->access_token = $user->token;
                $newUser->save();
                Auth::login($newUser, true);
            }
        }

        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))->update([
                'user_id' => Auth::id(),
                'temp_user_id' => null,
            ]);
            Session::forget('temp_user_id');
        }

        return session('link')
            ? redirect(session('link'))
            : redirect()->route(Auth::user()->user_type == 'seller' ? 'seller.dashboard' : 'dashboard');
    }

    public function mobileHandleProviderCallback(Request $request, string $provider)
    {
        $return_provider = '';
        $result = false;
        if ($provider) {
            $return_provider = $provider;
            $result = true;
        }
        return response()->json([
            'result' => $result,
            'provider' => $return_provider
        ]);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required_without:phone',
            'phone'    => 'required_without:email',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if ($request->get('phone') != null) {
            return ['phone' => "+{$request['country_code']}{$request['phone']}", 'password' => $request->get('password')];
        } elseif ($request->get('email') != null) {
            return $request->only($this->username(), 'password');
        }
    }

    /**
     * Check user's role and redirect user based on their role
    * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function authenticated()
    {
        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update(
                    [
                        'user_id' => Auth::id(),
                        'temp_user_id' => null
                    ]
                );

            Session::forget('temp_user_id');
        }

        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.dashboard');
        } else {

            if (session('link') != null) {
                return redirect(session('link'));
            } else {
                return redirect()->route('dashboard');
            }
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        flash(translate('Invalid login credentials'))->error();
        return back();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {
            $redirect_route = 'login';
        } else {
            $redirect_route = 'home';
        }

        //User's Cart Delete
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route($redirect_route);
    }

    public function account_deletion(Request $request)
    {
        $redirect_route = 'home';

        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        }

        // if (auth()->user()->provider) {
        //     $social_revoke =  new SocialRevoke;
        //     $revoke_output = $social_revoke->apply(auth()->user()->provider);

        //     if ($revoke_output) {
        //     }
        // }

        $auth_user = User::findOrFail(Auth::id());
        $auth_user->customer_products()->delete();

        User::destroy(Auth::id());

        auth()->guard()->logout();
        $request->session()->invalidate();

        flash("Your account deletion successfully done.")->success();
        return redirect()->route($redirect_route);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'account_deletion']);
    }
}
