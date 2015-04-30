<?php namespace Okie\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Okie\Conversation;
use Okie\Exceptions\ThreadException;
use Okie\Http\Requests;
use Okie\Inbox;
use Okie\User;
use Okie\Repositories\ThreadInterface;

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
				throw new ThreadException( 'INBOX', 'Inbox not found at the moment', 404 );

			return $this->responseInJSON( $inbox->paginate() );
		}
		else
		{
			if ( ! $inbox->whereSenderId( Auth::user()->id )->orWhere( 'recipient_id', '=', Auth::user()->id )->exists() )
				throw new ThreadException( 'INBOX', 'Inbox not found at the moment', 404 );

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
		$inbox = Inbox::find( $id );
		if ( is_null( $inbox ) )
			throw new ThreadException( 'INBOX', 'Inbox not found with id ' . $id, 404 );
		$this->checkIfAllowed( $inbox );

		return $this->responseInJSON( $inbox );
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
		$inbox = Inbox::find( $id );
		if ( is_null( $inbox ) )
			throw new ThreadException( 'INBOX', 'Inbox not found with id ' . $id, 404 );
		$this->checkIfAllowed( $inbox );


		return $this->responseInJSON( [
			'inbox'         => $inbox,
			'conversations' => $inbox->conversations()->paginate()->toArray()
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
			throw new ThreadException( 'INBOX', 'Cannot create same sender and recipient', 400 );
		}
		$inbox = Inbox::firstOrCreate( [
			'sender_id'    => Auth::user()->id,
			'recipient_id' => ( Auth::user()->isPermitted() ? $recipient->id : null ),
			'title'        => $this->changeTitle( $title )
		] );
		$conversation          = new Conversation;
		$conversation->user_id = Auth::user()->id;
		$conversation->body    = $this->filterBody( $request->input( 'message' ) );
		$conversation->type    = 'inbox';
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
	 * @param $string
	 *
	 * @return mixed
	 */
	private function changeTitle( $string )
	{
		$title = [
			'You' => Auth::user()->getFullName(),
			'YOU' => Auth::user()->getFullName(),
			'you' => Auth::user()->getFullName(),
		];

		return str_replace( array_keys( $title ), array_values( $title ), $string );
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
		$this->checkIfAllowed( $inbox );
		$conversation          = new Conversation;
		$conversation->user_id = Auth::user()->id;
		$conversation->body    = $this->filterBody( $request->input( 'message' ) );
		$conversation->type    = ( Auth::user()->isPermitted() ) ? 'inbox' : $conversation->responses['inbox'];
		$inbox->conversations()->save( $conversation );

		$findConve = Conversation::find( $conversation->id );
		$findConve->__set( 'reply', 'reply-reply' );

		return $this->responseSuccess( 'Successfully replied', $findConve );
	}

	/**
	 * Deleting conversation
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function destroyConversation( $id )
	{
		if( ! Auth::user()->isPermitted() )
			throw new ThreadException( 'INBOX', 'You are not permitted to delete a message', 401 );
		$conversation = Conversation::destroy( $id );
		if( ! $conversation )
			return $this->responseError( 'Someting went wrong on deleting a message', [ 'id' => $id ], 400 );

		return $this->responseSuccess( 'Successfully remove message' );
	}

	/**
	 * Check if user is allowed to
	 *
	 * @param $inbox
	 *
	 * @throws \Okie\Exceptions\ThreadException
	 */
	protected function checkIfAllowed( $inbox )
	{
		if( Auth::user()->isUser() && ( $inbox->sender_id != Auth::id() ) )
			throw new ThreadException( 'INBOX', 'You are not allowed here', 401, 'Opps', [
				'sender' => $inbox->sender_id,
				'recipient' => $inbox->recipient_id,
				'user' => Auth::id()
			] );
	}

}
