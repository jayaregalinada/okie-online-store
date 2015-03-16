<?php namespace Okie\Repositories;

use Okie\User;
use GuzzleHttp\Client;

class UserRepository {

	private function getAvatarUrl( $url )
	{
		// Assuming its URL is http://graph.facebook.com/v2.2/1239783/picture?type=normal
		$client = new Client();
		$response = $client->get( $url . '&redirect=0' );
		return $response->json()['data']['url'];
	}

	public function findByUserNameOrCreate( $userData, $provider )
	{
		$user = User::where( 'provider_id', '=', $userData->id )->first();
		if( !$user )
		{
			$user = User::create([
				'provider_id'   => $userData->id,
				'email'         => $userData->email,
				'avatar'        => $this->getAvatarUrl( $userData->avatar ),
				'active'        => 1,
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

	public function checkIfUserNeedsUpdating( $userData, $user )
	{
		$socialData = 
		[
			'avatar'        => $this->getAvatarUrl( $userData->avatar ),
			'email'         => $userData->email,
			'first_name'    => $userData->user['first_name'],
			'last_name'     => $userData->user['last_name'],
		];
		$dbData = 
		[
			'avatar'        => $user->avatar,
			'email'         => $user->email,
			'first_name'    => $user->first_name,
			'last_name'     => $user->last_name,
		];

		if (!empty(array_diff($socialData, $dbData))) 
		{
			$user->avatar       = $userData->avatar;
			$user->email        = $userData->email;
			$user->first_name   = $userData->user['first_name'];
			$user->last_name    = $userData->user['last_name'];
			$user->save();
		}
	}

}
