<?php namespace Okie\Http\Controllers;

/**
 * ===================================== *
 * This controller is for testing only
 * ===================================== *
 */

use Illuminate\Http\Request;
use Request as RequestFactory;
use Auth;
use Okie\MessageStatus;
use Okie\Message;
use Okie\User;
use Okie\Thread;
use Okie\Exceptions\ThreadException;

class TestController extends Controller {

	
	public function __construct()
	{
		$this->middleware( 'auth' );
		$this->middleware( 'admin' );
	}

	public function postPost( Request $request )
	{
		return $request->all();
	}

	public function getMessages()
	{
		return Message::with(['user', 'product'])->get();
	}

	public function getInquiries()
	{
		return User::has('messages')->with(['messages' => function( $query )
		{
			$query->whereType( 'inquire' )->orderBy('created_at', 'desc');
		}, 'messages.product'])->paginate();
	}

	public function getUser()
	{
		return User::has('messages')->with(['messages' => function( $query )
		{
			$query->whereType( 'inquire' );
		}, 'messages.product'])->get();
	}

	public function getThreads( $id )
	{
		$i = [];
		$allMessagesId = \Okie\Thread::find( $id )->messages()->lists('message_id');
		foreach ($allMessagesId as $key => $value)
		{
			$find = \Okie\MessageStatus::whereMessageId( $value );
			$i[] = $find->update( [ 'status' => 1 ] );
		}
		return $i;
	}

	public function getCheckThread( $id )
	{
		return \Okie\Thread::with(['user', 'messages'])->find( $id );
	}

	public function getThreadsByUser( $id )
	{
		return( \Okie\Thread::whereUserId( $id )->latest( 'updated_at' )->paginate() );
	}

	public function getUnreadReplies( $user_id )
	{
		return MessageStatus::whereStatus( 0 )->get();
	}

	public function getDelivered()
	{
		return Thread::with( [ 'messages' => function ( $q ) {
				$q->latest();
			}, 'user', 'product' ])->whereType( 'delivered' )->latest( 'updated_at' )->paginate();
	}

	public function getForceLogin( $id )
	{
		if( app()->environment('local') )
			Auth::loginUsingId( $id );
			return redirect( route('me') );

		return response(['error' => [ 'message' => 'This feature is only available on local mode' ] ], 400);
	}

	public function getThreadTake( $thread_id, $message )
	{
		return( Thread::with(['messages' => function( $q ) use ( $message ) {
			$q->skip( $message - 1 )->take( 15 )->get();
		} ])->find( $thread_id )
		);
	}

	public function getCheckThreadStatus( $id )
	{
		dd( Thread::find( $id )->isInquiry() );
	}

	public function getRequest()
	{
		return $this->responseInJSON(['error' => 'hi']);
	}

	public function getMessagesCount( $status )
	{
		return MessageStatus::getReplyOnType( $status );
	}

	public function getMessage( $id )
	{
		$a = Message::with( ['thread'] )->find( $id );
		return dd( $a->getRelation( 'thread' )->first() );
	}

	public function getInbox()
	{
		$i = MessageStatus::whereStatus( 0 );
		
		return $i->whereType( 'inbox' )->count();
	}

	public function getCategories()
	{
		return \Okie\Product::paginate();
	}

}
