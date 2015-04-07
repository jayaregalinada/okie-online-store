<?php namespace Okie\Services\User;

use Illuminate\Contracts\Auth\Guard;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Okie\Repositories\UserRepository;

class AuthenticateUser {

	/**
	 * @var \Laravel\Socialite\Contracts\Factory
	 */
	private $socialite;

	/**
	 * @var \Illuminate\Contracts\Auth\Guard
	 */
	private $auth;

	/**
	 * @var \Okie\Repositories\UserRepository
	 */
	private $users;

	/**
	 * Create new instance
	 *
	 * @param Socialite      $socialite
	 * @param Guard          $auth
	 * @param UserRepository $users
	 */
	public function __construct( Socialite $socialite, Guard $auth, UserRepository $users )
	{
		$this->socialite = $socialite;
		$this->users     = $users;
		$this->auth      = $auth;
	}

	/**
	 * Execute request
	 *
	 * @param $request
	 * @param $listener
	 * @param $provider
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function execute( $request, $listener, $provider )
	{
		if ( ! $request ) return $this->getAuthorizationFirst( $provider );
		$user = $this->users->findByUserNameOrCreate( $this->getSocialUser( $provider ), $provider );
		$this->auth->login( $user, true );

		return $listener->userHasLoggedIn( $user );
	}

	/**
	 * @param  string  $provider
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	private function getAuthorizationFirst( $provider )
	{
		return $this->socialite->driver( $provider )->redirect();
	}

	/**
	 * @param  string  $provider
	 *
	 * @return \Laravel\Socialite\Contracts\User
	 */
	private function getSocialUser( $provider )
	{
		return $this->socialite->driver( $provider )->user();
	}

}