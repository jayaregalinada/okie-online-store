<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okie\Thread;
use Okie\MessageReadState;
use Okie\MessageStatus;
use Auth;

class ThreadController extends Controller {

	/**
	 * Index page
	 *
	 * @return \Okie\Thread
	 */
	public function index()
	{
		return Thread::with( [ 'messages' => function ( $q ) {
			$q->latest();
		}, 'user' ])->paginate();
	}

	/**
	 * Show thread
	 *
	 * @param  integer $id
	 *
	 * @return \Okie\Thread
	 */
	public function show( $id )
	{
		return Thread::with( [ 'user', 'messages' ] )->find( $id );
	}

	/**
	 * Get all inquiries by user
	 *
	 * @return \Okie\Thread
	 */
	public function getAllInquiries()
	{
		if ( Auth::user()->isAdmin() )
			return Thread::with( [ 'messages' => function ( $q ) {
				$q->latest();
			}, 'user', 'product' ])->whereType( 'inquire' )->latest( 'updated_at' )->paginate();

		return Thread::whereUserId( Auth::user()->id )->with( [ 'product', 'user', 'messages' => function( $q ){
			$q->latest();
		} ] )->where( 'type', '=', 'inquire' )->orWhere( 'type', '=', 'delivered' )->latest( 'updated_at' )->paginate();
	}

	/**
	 * Get inquiries by thread id
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  integer  $id
	 *
	 * @return \Okie\Thread
	 */
	public function getInquiryByThread( Request $request, $id )
	{
		return Thread::with( [ 'messages' => function ( $q ) {
			$q->latest();
		}, 'user', 'product' ])->find( $id );
	}

	/**
	 * Get messages by thread id
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  integer  $id
	 *
	 * @return \Okie\Thread
	 */
	public function getMessagesByThread( Request $request, $id )
	{
		$this->readAllMessages( $id );

		return Thread::find( $id )->messages()->latest()->paginate();
	}

	/**
	 * Read all messages by thread id
	 *
	 * @param  integer $id
	 *
	 * @return array
	 */
	protected function readAllMessages( $id )
	{
		$t = [];
		$m = [];
		$allMessagesId = Thread::find( $id )->messages()->lists('message_id');
		foreach ($allMessagesId as $key => $value)
		{
			if( Auth::user()->isAdmin() )
			{
				$find       = MessageStatus::whereMessageId( $value )
											 ->where( 'user_id', '=', Auth::user()->id )
											 ->where( 'type', 'NOT LIKE', '%.reply%');
				$findThread = MessageStatus::whereThreadId( $id )
											 ->where( 'user_id', '=', Auth::user()->id )
											 ->where( 'type', 'NOT LIKE', '%.reply%');
			}
			else
			{
				$find       = MessageStatus::whereMessageId( $value )
											 ->where( 'user_id', '=', Auth::user()->id )
											 ->where( 'type', 'LIKE', '%.reply%');
				$findThread = MessageStatus::whereThreadId( $id )
											 ->where( 'user_id', '=', Auth::user()->id )
											 ->where( 'type', 'LIKE', '%.reply%');
			}
			$t[]        = $findThread->update( [ 'status' => 1 ] );
			$m[]        = $find->update( [ 'status' => 1 ] );
			$this->readMessageByUser( $value, Auth::user()->id );
		}

		return [
			'messages' => $m,
			'thread' => $t
		];
	}

	/**
	 * Add a read statement
	 *
	 * @param  integer $message_id
	 * @param  integer $user_id
	 *
	 * @return \Okie\MessageReadState
	 */
	protected function readMessageByUser( $message_id, $user_id )
	{
		return MessageReadState::firstOrCreate( [ 'message_id' => $message_id, 'user_id' => $user_id ] );
	}

	/**
	 * Get all delivered thread
	 *
	 * @return \Okie\Thread
	 */
	public function getAllDelivered()
	{
		return Thread::with( [ 'messages' => function ( $q ) {
				$q->latest();
			}, 'user', 'product' ])->whereType( 'delivered' )->latest( 'updated_at' )->paginate();
	}

	public function updateToDelivered( Request $request )
	{
		$thread = Thread::find( $request->input( 'id' ) );
		$thread->type = 'delivered';
		$thread->name = '[DELIVERED] ' . $thread->name;
		if( $thread->save() )
			return response()->json( [
				'success' => [
					'message' => 'Successfully transfered to label [delivered]',
					'data' => Thread::find( $request->input( 'id' ) )
				]
			] );

		return response( [ 
			'error' => [
				'message' => 'Something went wrong in updating thread # ' . $request->input( 'id' ),
				'data' => $thread 
			]
		], 400 );
	}

	public function getMessagesByOffset( Request $request )
	{
		$take = ( $request->input( 'take' ) ? $request->input( 'take' ) : 15 );
		$offset = $request->input( 'offset' );
		
		return Thread::with( [
			'messages' => function( $q ) use ( $offset, $take ) {
				$q->skip( $offset - 1 )
				  ->take( $take )
				  ->get();
			}
		])->find( $request->input( 'thread' ) );
	}

	public function getAllInboxes()
	{
		if( Auth::user()->isAdmin() )
		{
			return Thread::with( [ 'messages' => function ( $q ) {
				$q->latest();
			}, 'user', 'product' ])->whereType( 'inbox' )->latest( 'updated_at' )->paginate();
		}
		else
		{
			return Thread::with( [ 'messages' => function ( $q ) {
				$q->latest();
			}, 'user', 'product' ])->whereType( 'inbox' )->whereUserId( Auth::user()->id )->latest( 'updated_at' )->paginate();
		}
	}


}
