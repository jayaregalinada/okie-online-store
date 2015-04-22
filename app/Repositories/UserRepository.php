<?php namespace Okie\Repositories;

use Hash;
use Crypt;
use Okie\User;
use GuzzleHttp\Client;
use Okie\Services\Registrar;
use Okie\Exceptions\UserException;

class UserRepository {

	/**
	 * @var integer
	 */
	protected $imageSizes = 400;

	/**
	 * Get the Avatar URL
	 *
	 * @param string $url
	 *
	 * @return mixed|json
	 */
	protected function getAvatarUrl( $url )
	{
		// Assuming its URL is http://graph.facebook.com/v2.2/{USER_FACEBOOK_ID}/picture?type=normal
		$client = new Client();
		$response = $client->get( $url . '&redirect=0&width=' . $this->imageSizes .'&height=' . $this->imageSizes );

		return $response->json()[ 'data' ][ 'url' ];
	}

	/**
	 * Find the user if exists
	 * or else create user
	 *
	 * @param  object $userData
	 * @param  string $provider Provider's name [facebook,google,]
	 *
	 * @return \Okie\User
	 */
	public function findByUserNameOrCreate( $userData, $provider )
	{
		$user = User::whereProviderId( $userData->id )->first();
		if( ! $user )
		{
			if( is_null( User::find( 1 ) ) )
			{
				return $this->createAdmin( $userData, $provider );
			}
			else
			{	
				return $this->createUser( $userData, $provider );
			}
		}
		else
		{
			return $this->checkIfUserNeedsUpdating( $userData, $user );
		}
	}

	/**
	 * Check if user have the similar data
	 * in database and in social provider
	 *
	 * @param  object $userData
	 * @param  \Okie\User $user
	 *
	 * @return \Okie\User
	 */
	public function checkIfUserNeedsUpdating( $userData, $user )
	{
		$socialData = 
		[
			'avatar'         => $this->getAvatarUrl( $userData->avatar ),
			'email'          => $userData->email,
			'first_name'     => $userData->user[ 'first_name' ],
			'last_name'      => $userData->user[ 'last_name' ],
		];
		$dbData = 
		[
			'avatar'        => $user->avatar,
			'email'         => $user->email,
			'first_name'    => $user->first_name,
			'last_name'     => $user->last_name,
		];

		if ( ! empty( array_diff( $socialData, $dbData ) ) )
		{
			$user->avatar       = $this->getAvatarUrl( $userData->avatar );
			$user->email        = $userData->email;
			$user->first_name   = $userData->user[ 'first_name' ];
			$user->last_name    = $userData->user[ 'last_name' ];
		}

		$user->provider_token   = Crypt::encrypt( $userData->token );
		$user->save();

		return $user;
	}

	/**
	 * @param $userData
	 * @param $provider
	 *
	 * @return \Okie\User
	 * @throws \Okie\Exceptions\UserException
	 */
	private function createAdmin( $userData, $provider )
	{
		$user = new Registrar;
		$password = $userData->token . $userData->id . $userData->name;
		$data = [
			'permission'     => 0,
			'provider_id'    => $userData->id,
			'email'          => $userData->email,
			'avatar'         => $this->getAvatarUrl( $userData->avatar ),
			'verified'       => $userData->user['verified'],
			'provider'       => $provider,
			'first_name'     => $userData->user[ 'first_name' ],
			'last_name'      => $userData->user[ 'last_name' ],
			'gender'         => $userData->user[ 'gender' ],
			'link'           => $userData->user[ 'link' ],
			'password'       => $password,
			'password_confirmation' => $password,
			'provider_token' => $userData->token
		];
		if( $user->validator( $data )->fails() )
			throw new UserException( $user->validator( $data )->errors(), 500 );

		return $user->create( $data );
	}

	/**
	 * @param $userData
	 * @param $provider
	 *
	 * @return \Okie\User
	 * @throws \Okie\Exceptions\UserException
	 */
	private function createUser( $userData, $provider )
	{
		$user = new Registrar;
		$password = $userData->token . $userData->id . $userData->name;
		$data = [
			'permission'     => 1,
			'provider_id'    => $userData->id,
			'email'          => $userData->email,
			'avatar'         => $this->getAvatarUrl( $userData->avatar ),
			'verified'       => $userData->user['verified'],
			'provider'       => $provider,
			'first_name'     => $userData->user['first_name'],
			'last_name'      => $userData->user['last_name'],
			'gender'         => $userData->user['gender'],
			'link'           => $userData->user['link'],
			'password'       => $password,
			'password_confirmation' => $password,
			'provider_token' => $userData->token
		];
		if( $user->validator( $data )->fails() )
			throw new UserException( $user->validator( $data )->errors(), 500 );
		
		return $user->create( $data );
	}

}
