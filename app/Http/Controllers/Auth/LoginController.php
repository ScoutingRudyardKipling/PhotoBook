<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staf\StafUser;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use ScoutingRudyardKipling\SOLOpenIdClient;
use ScoutingRudyardKipling\SOLOpenIdUser;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \ErrorException
     */
    public function snlLogin(Request $request)
    {
        if (config('auth.useSol') === false) {
            return redirect()->route('login')->withErrors(['msg', 'The usage of sol login is not enabled.']);
        }
        $openid = $this->getOpenIdClient();

        if (!$openid->mode && $request->has('sol-user')) {
            $username = $request->input('sol-user');

            return redirect()->to($openid->setUserIdentity($username)->authUrl());
        } elseif (!!$openid->mode) {
            if ($openid->mode == 'cancel') {
                // user cancelled logging in

                return redirect()->route('login')->withErrors(['msg', 'Login canceled']);
            } elseif ($openid->validate()) {
                // user login confirmed by Scouting Nederland, let's proceed!

                $openIdUser = $openid->getValidatedUser();

                // Either create a new user or link the returned SNL-user to one of your registered users.
                // Notice that SNL only confirmed that the user is who it claims to be and that he/she is an active
                // member of Scouting Nederland. You have to deal with authorisation yourself for instance to make sure
                // the authenticated user is a member of your scouting club.

                if (config('auth.useStaf') === false) {
                    $this->loginSolUser($openIdUser);
                } elseif (StafUser::whereEmail($openIdUser->email)->count() >= 1) {
                    $this->loginSolUser($openIdUser);
                }

                return redirect()->route('login')->withErrors(['msg', 'You are not in this organisation.']);
            }

            return redirect()->route('login')->withErrors(['msg', 'Login failed.']);
        }

        // show a form where the user can provide his/her SNL username
        return redirect()->route('login');
    }


    private function loginSolUser(SOLOpenIdUser $openIdUser)
    {
        $user = User::updateOrCreate(
            [
                'email' => $openIdUser->email,
            ],
            [
                'name'               => $openIdUser->fullName,
                'birth_date'         => $openIdUser->birthDate,
                'gender'             => $openIdUser->gender,
                'preferred_language' => $openIdUser->preferredLanguage,
                'password'           => 'invalid',
            ]
        );
        if ($user->roles()->count() == 0) {
            $user->assignRole('Subscriber');
        }
        Auth::login($user);
        return redirect()->route('home');
    }

    /**
     * @return SOLOpenIdClient
     */
    private function getOpenIdClient(): SOLOpenIdClient
    {
        try {
            return new SOLOpenIdClient(config('app.openid_return_url'));
        } catch (\Exception $e) {
            $code    = 500;
            $message = 'Quit due to misconfiguration.';
            report(new Exception($code . ': ' . $message));
            abort($code);
        }
    }
}
