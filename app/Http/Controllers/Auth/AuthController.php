<?php namespace Okie\Http\Controllers\Auth;

use Session;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Okie\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Registrar;
use Okie\Services\User\AuthenticateUser;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

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
		if( $user->updated_at == $user->created_at )
			// User is new
			Session::flash( 'message', 'Welcome, ' . $user->first_name . ' to '. config( 'app.title' ) );
		else
			Session::flash( 'message', 'Its good to be back, '. $user->first_name );
		Session::put( 'me', $user->toArray() );

		return redirect( '/' );
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getLogin()
	{
		return view('auth.login');
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogout()
	{
		Session::forget( 'me' );
		$this->auth->logout();

		return redirect( property_exists( $this, 'redirectAfterLogout' ) ? $this->redirectAfterLogout : '/' );
	}

}
