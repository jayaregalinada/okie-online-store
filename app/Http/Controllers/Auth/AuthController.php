<?php namespace Okie\Http\Controllers\Auth;

use Okie\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Okie\Services\User\AuthenticateUser;
use Session;

class AuthController extends Controller {

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

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 *
	 * @return void
	 */
	public function __construct( Guard $auth, Registrar $registrar )
	{
		$this->auth         = $auth;
		$this->registrar    = $registrar;
		$this->middleware( 'guest', [ 'except' => 'getLogout' ]);
	}

	/**
	 * @param \Okie\Services\User\AuthenticateUser $authenticateUser
	 * @param \Illuminate\Http\Request          $request
	 * @param null             $provider
	 *
	 * @return \Okie\Services\User\AuthenticateUser|mixed
	 */
	public function login( AuthenticateUser $authenticateUser, Request $request, $provider = null )
	{
		return $authenticateUser->execute( $request->all(), $this, $provider );
	}

	/**
	 * The user has been logged in
	 *
	 * @param \Okie\User $user
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function userHasLoggedIn( $user )
	{
		Session::flash( 'message', 'Welcome, ' . $user->name );
		
		return redirect( '/home' );
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getLogin()
	{
		return view('auth.login');
	}

}
