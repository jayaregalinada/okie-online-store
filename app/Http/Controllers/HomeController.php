<?php namespace Okie\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Okie\MessageStatus;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view( 'home' );
	}

	/**
	 * Get Profile information
	 *
	 * @param  Request $request
	 *
	 * @return mixed
	 */
	public function profile( Request $request )
	{
		if( $request->ajax() )
			return $this->getResponseProfile();

		return view( 'profile.index' );
	}

	/**
	 * Get message view
	 *
	 * @return \Illuminate\View\View
	 */
	public function messages()
	{
		return view( 'messages.messages' );
	}

	/**
	 * Profile information in array
	 *
	 * @return array
	 */
	private function getResponseProfile()
	{
		$response = [
			'user' => Auth::user(),
		];
		if( Auth::user()->isAdmin() )
			return array_add( $response, 'messages', MessageStatus::countUnreadMessages() );
		else
			return array_add( $response, 'messages', MessageStatus::countUnreadReplyMessages( Auth::user()->id ) );

		return $response;
	}

	/**
	 * @return mixed
	 */
	public function getAllRoutes()
	{
		$obj = new \stdClass;
		$obj->inquiry = route( 'inquiry', null );

		return $this->responseInJSON( $obj );
	}


}
