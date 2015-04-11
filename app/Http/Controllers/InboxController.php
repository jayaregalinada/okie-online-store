<?php namespace Okie\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Okie\Conversation;
use Okie\Exceptions\InboxException;
use Okie\Http\Requests;
use Okie\Inbox;
use Okie\User;

class InboxController extends Controller {

	public function __contruct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * @return mixed
	 */
	public function getAll()
	{
		$inbox = Inbox::latest( 'updated_at' );
		if ( Auth::user()->isPermitted() )
		{
			if ( ! $inbox->exists() )
				return $this->responseInJSON( [ 'error' => [
					'message' => 'Inbox not found at the moment',
					'code'    => 404 ]
				], 404 );

			return $this->responseInJSON( $inbox->paginate() );
		}
		else
		{
			if ( ! $inbox->whereSenderId( Auth::user()->id )->orWhere( 'recipient_id', '=', Auth::user()->id )->exists() )
				return $this->responseInJSON( [ 'error' => [
					'message' => 'Inbox not found at the moment',
					'code'    => 404 ]
				], 404 );

			return $this->responseInJSON( $inbox->whereSenderId( Auth::user()->id )->orWhere( 'recipient_id', '=', Auth::user()->id )->paginate() );
		}
	}


	/**
	 * @param $id
	 *
	 * @return mixed|\Okie\Inbox
	 */
	public function get( $id )
	{
		if ( is_null( Inbox::find( $id ) ) )
			return $this->responseInJSON( [ 'error' => [
				'message' => 'Inbox not found with id ' . $id,
				'id'      => $id,
				'code'    => 404 ]
			], 404 );

		return $this->responseInJSON( Inbox::find( $id ) );
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getMessagesView()
	{
		return view( 'messages.a_messages_inbox' );
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getConversationView()
	{
		return view( 'messages.a_conversation_inbox' );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getConversations( $id )
	{
		if ( is_null( Inbox::find( $id ) ) )
			return $this->responseInJSON( [ 'error' => [
				'message' => 'Inbox not found with id ' . $id,
				'id'      => $id,
				'code'    => 404 ]
			], 404 );

		return $this->responseInJSON( [
			'inbox'         => Inbox::find( $id ),
			'conversations' => Inbox::find( $id )->conversations()->paginate()->toArray()
		] );
	}

	/**
	 * Create inbox
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\InboxException
	 */
	public function create( Request $request )
	{
		$recipient = ( Auth::user()->isPermitted() ? User::find( $request->input( 'recipient' ) ) : null );
		$title = ( $request->input( 'subject' ) ? $request->input( 'subject' ) : 'Message from ' . Auth::user()->getFullName() );
		if ( Auth::user()->isPermitted() && Auth::user()->id == $recipient->id )
		{
			throw new InboxException( 'Cannot create same sender and recipient', 400 );
		}
		$inbox = Inbox::firstOrCreate( [
			'sender_id'    => Auth::user()->id,
			'recipient_id' => ( Auth::user()->isPermitted() ? $recipient->id : null ),
			'title'        => $title
		] );
		$conversation = new Conversation;
		$conversation->user_id = Auth::user()->id;
		$conversation->body = $this->filterBody( $request->input( 'message' ) );
		$conversation->type = 'inbox';
		$inbox->conversations()->save( $conversation );

		return $this->responseInJSON( [
			'success' => [
				'message' => 'Sent message to ' . ( Auth::user()->isPermitted() ? $recipient->getFullName() : 'admin' ) . '. Please always check your messages for possible response',
				'data'    => [
					'inbox'        => $inbox,
					'conversation' => $conversation
				]
			]
		] );
	}

	/**
	 * Reply to Inbox
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 */
	public function reply( Request $request )
	{
		$inbox = Inbox::find( $request->input( 'inbox' ) );
		$conversation = new Conversation;
		$conversation->user_id = Auth::user()->id;
		$conversation->body = $this->filterBody( $request->input( 'message' ) );
		$conversation->type = ( Auth::user()->isPermitted() ) ? 'inbox' : $conversation->responses['inbox'];
		$inbox->conversations()->save( $conversation );

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfully replied',
			'data'    => Conversation::find( $conversation->id ) ]
		] );
	}

}
