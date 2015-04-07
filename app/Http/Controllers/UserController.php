<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\User;
use Okie\MessageStatus;
use Okie\Http\Requests;
use Illuminate\Http\Request;


class UserController extends Controller {

	/**
	 * Create new instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	public function getPermission()
	{
		return [
			'admin' => Auth::user()->isAdmin(),
			'user'  => Auth::user()->isUser(),
			'moderator' => Auth::user()->isModerator(),
			'permitted' => Auth::user()->isPermitted()
		];
	}

	/**
	 * Get the user information
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\View\View|\Illuminate\Contracts\Routing\ResponseFactory
	 */
	public function getUserInfo( Request $request )
	{
		if ( $request->ajax() )
			return response()->json( [
				'user'     => Auth::user(),
				'messages' => $this->userMessages( Auth::user() ),
			] )->setCallback( $request->input( 'callback' ) );
		
		return view( 'profile.index' );
	}

	/**
	 * @param  Auth $user
	 *
	 * @return \stdClass|object
	 */
	protected function userMessages( $user )
	{
		$object = new \stdClass();
		if ( $user->isAdmin() )
		{
			$object->inquiry    = MessageStatus::whereStatus( 0 )->where( 'type', 'LIKE', '%inquiry%' )->count();
			$object->inbox      = MessageStatus::whereStatus( 0 )->whereUserId( $user->id )->where( 'type', 'LIKE', '%inbox%' )->count();
			$object->deliver    = MessageStatus::whereStatus( 0 )->where( 'type', 'LIKE', '%deliver%' )->count();
			$object->all        = array_sum( [ $object->inquiry, $object->inbox, $object->deliver ] );
		}
		else
		{
			$object->inquiry    = MessageStatus::whereStatus( 0 )->whereType( 'inquiry.reply' )->count();
			$object->inbox      = MessageStatus::whereStatus( 0 )->where( 'type', 'LIKE', '%inbox%' )->whereUserId( $user->id )->count();
			$object->all        = array_sum( [ $object->inquiry, $object->inbox ] );
		}

		return $object;
	}

	public function getFriendsList()
	{
		if( Auth::user()->isAdmin() )
			return $this->responseInJSON( User::paginate() );

		// return $this->responseInJSON( [ ] );
	}



}
