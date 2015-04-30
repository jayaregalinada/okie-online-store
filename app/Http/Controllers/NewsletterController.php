<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\Exceptions\NewsletterException;
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
				'title' => 'Nice!',
				'message' => 'Successfully subscribe to our Newsletter',
				'emails' => Newsletter::whereUserId( Auth::id() )->get() ]
			] );

		return $this->responseInJSON( [ 'success' => [
			'title'   => 'Nice!',
			'message' => 'Successfully subscribe to our Newsletter',
			'data'    => $model ]
		] );
	}

	/**
	 * @return mixed
	 */
	public function getSubscribeByUser()
	{
		if( ! Auth::check() )
			return $this->responseInJSON( ['error' => [
				'title'   => 'Opps',
				'message' => 'Sorry you have to log in first' ]
			] );

		return $this->responseSuccess( 'Successfull get all email subscribtion by user', Newsletter::whereUserId( Auth::id() )->get() );
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\NewsletterException
	 */
	public function unsubscribeEmail( Request $request )
	{
		$newsletter = Newsletter::whereEmail( $request->input( 'email' ) );
		if( ! $newsletter->exists() )
			throw new NewsletterException( $request->input( 'email' ), 'No such email exists' );
		if( ! $newsletter->delete() )
			return $this->responseError( 'Something went wrong on unsubscribing an email', [ 'email' => $request->input( 'email' ) ] );

		return $this->responseSuccess( 'Successfully unsubscribe', Newsletter::whereUserId( Auth::id() )->get() );
	}

}
