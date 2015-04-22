<?php namespace Okie\Services;

use Hash;
use Crypt;
use Okie\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator( array $data )
	{
		return Validator::make( $data, [
			'first_name' => 'required|max:255',
			'last_name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
			'avatar' => 'required|url',
		] );
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 *
	 * @return User
	 */
	public function create( array $data )
	{
		return User::create( [
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'email' => $data['email'],
			'password' => Hash::make( $data['password'] ),
			'permission' => $data[ 'permission' ],
			'provider_id' => $data[ 'provider_id' ],
			'avatar' => $data[ 'avatar' ],
			'verified' => $data[ 'verified' ],
			'provider' => $data[ 'provider' ],
			'gender' => $data[ 'gender' ],
			'link' => $data[ 'link' ],
			'provider_token' => Crypt::encrypt( $data[ 'provider_token' ] )
		] );
	}
}
