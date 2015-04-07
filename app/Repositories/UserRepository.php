<?php namespace Okie\Repositories;

use Okie\User;
use GuzzleHttp\Client;

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
		$user = User::where( 'provider_id', '=', $userData->id )->first();
		if( ! $user )
		{
			$user = User::create([
				'provider_id'   => $userData->id,
				'email'         => $userData->email,
				'avatar'        => $this->getAvatarUrl( $userData->avatar ),
				'verified'      => $userData->verified,
				'provider'      => $provider,
				'first_name'    => $userData->user['first_name'],
				'last_name'     => $userData->user['last_name'],
				'gender'        => $userData->user['gender'],
				'link'          => $userData->user['link']
			]);
		}
		$this->checkIfUserNeedsUpdating( $userData, $user );

		return $user;
	}

	/**
	 * Check if user have the similar data
	 * in database and in social provider
	 *
	 * @param  object $userData
	 * @param  \Okie\User $user
	 *
	 * @return void
	 */
	public function checkIfUserNeedsUpdating( $userData, $user )
	{
		$socialData = 
		[
			'avatar'        => $this->getAvatarUrl( $userData->avatar ),
			'email'         => $userData->email,
			'first_name'    => $userData->user[ 'first_name' ],
			'last_name'     => $userData->user[ 'last_name' ],
		];
		$dbData = 
		[
			'avatar'        => $user->avatar,
			'email'         => $user->email,
			'first_name'    => $user->first_name,
			'last_name'     => $user->last_name,
		];

		if ( !empty( array_diff( $socialData, $dbData ) ) )
		{
			$user->avatar       = $this->getAvatarUrl( $userData->avatar );
			$user->email        = $userData->email;
			$user->first_name   = $userData->user[ 'first_name' ];
			$user->last_name    = $userData->user[ 'last_name' ];
			$user->save();
		}
	}

}
