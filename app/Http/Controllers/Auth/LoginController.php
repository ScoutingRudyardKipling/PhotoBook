<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use ScoutingRudyardKipling\SOLOpenIdClient;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \ErrorException
     */
    public function SNLLogin(Request $request)
    {
        $openid = $this->getOpenIdClient();

        if (!$openid->mode && $request->has('username')) {
            $username = $request->input('username');

            return redirect()->to($openid->setUserIdentity($username)->authUrl());
        } elseif (!!$openid->mode) {
            if ($openid->mode == 'cancel') {
                // user cancelled logging in

                return redirect()->route('login');
            } elseif ($openid->validate()) {
                // user login confirmed by Scouting Nederland, let's proceed!

                $user = $openid->getValidatedUser();

                // Either create a new user or link the returned SNL-user to one of your registered users.
                // Notice that SNL only confirmed that the user is who it claims to be and that he/she is an active
                // member of Scouting Nederland. You have to deal with authorisation yourself for instance to make sure
                // the authenticated user is a member of your scouting club.

                dd('login confirmed');

                return redirect()->route('home');
            }

            // login failed

            return view('auth.login'); // todo - show error message
        }

        // show a form where the user can provide his/her SNL username
        return redirect()->route('login');
    }

    /**
     * @return SOLOpenIdClient
     */
    private function getOpenIdClient() : SOLOpenIdClient
    {
        try {
            return new SOLOpenIdClient(config('app.openid_return_url'));
        } catch (\Exception $e) {
            exit('Quit due to misconfiguration. Please contact administrator or call \'schrubbery!!!\' very loud.');
        }
    }
}
