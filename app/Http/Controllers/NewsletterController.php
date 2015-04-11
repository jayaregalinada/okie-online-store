<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Okie\Newsletter;
use Illuminate\Http\Request;
use Okie\Http\Requests\NewsletterRequest;

class NewsletterController extends Controller {

	/**
	 * @param \Okie\Http\Requests\NewsletterRequest $newsletterRequest
	 *
	 * @return array
	 */
	public function subscribeToNewsletter( NewsletterRequest $newsletterRequest )
	{
		$model = Newsletter::firstOrCreate( [
			'email' => $newsletterRequest->input( 'email' ),
			'user_id' => ( Auth::check() ) ? Auth::id() : null
		] );

		if( Auth::check() )
			return $this->responseInJSON( [ 'success' => [
				'message' => 'Successfully subscribe to our Newsletter',
				'emails' => Newsletter::whereUserId( Auth::id() )->get() ]
			] );

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfully subscribe to our Newsletter', ]
		] );
	}

	public function getSubscribeByUser()
	{
		if( ! Auth::check() )
			return $this->responseInJSON( ['error' => [
				'message' => 'Sorry you have to log in first' ]
			] );

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfull get all email subscribtion by user',
			'data' => Newsletter::whereUserId( Auth::id() )->get() ]
		] );
	}

}
