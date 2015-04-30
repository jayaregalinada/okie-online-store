<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\Deliver;
use Okie\Conversation;
use Okie\Inquiry;
use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okie\Exceptions\ThreadException;
use View;

class DeliverController extends Controller {

	/**
	 * Create new instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
		$this->middleware( 'admin' );
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\ThreadException
	 */
	public function create( Request $request )
	{
		$inquiry = Inquiry::find( $request->input( 'inquiry' ) );
		if( is_null( $inquiry ) )
			throw new ThreadException( 'DELIVER', 'Inquiry with ID: '. $request->input( 'inquiry' ) .' not found', 404 );
		if( ! Auth::user()->isPermitted() )
			throw new ThreadException( 'DELIVER', 'You are not allowed to mark this inquiry to delivered', 400 );
		$deliver = Deliver::firstOrCreate([
			'title'      => '[DELIVERED] ' . $inquiry->title,
			'product_id' => $inquiry->product_id,
			'user_id'    => $inquiry->inquisition_id,
			'confirm_id' => Auth::user()->id
		]);
		foreach( $inquiry->conversations as $key => $value )
		{
			$value->taggable()->delete(); // Removing conversations on Inquiry
			$deliver->conversations()->save( $value ); // Saving conversations on Deliver
		}
		// Updating `type` attribute to deliver|deliver-reply
		foreach ( $deliver->conversations as $key => $value )
		{
			$value->update( [
				'type' => ( ( $value->type == 'inquiry') ? 'deliver' : 'deliver-reply' )
			] );
		}

		return $this->responseSuccess( 'Successfully moved to delivered', [
			'deliver' => $deliver,
			'conversation' => $deliver->conversations
		] );
	}

	public function reply( Request $request )
	{
		$deliver = Deliver::find( $request->input( 'deliver' ) );
		if( is_null( $deliver ) )
			throw new ThreadException( 'INQUIRY', 'Inquiry not found' );
		$conversation           = new Conversation;
		$conversation->user_id  = Auth::user()->id;
		$conversation->body     = $this->filterBody( $request->input( 'message' ) );
		$conversation->type     = 'deliver-reply';
		$deliver->conversations()->save( $conversation );

		return $this->responseSuccess( 'Successfully replied', Conversation::find( $conversation->id ) );
	}

	/**
	 * @return mixed|\Okie\Deliver
	 */
	public function getAll()
	{
		$deliver = Deliver::latest( 'updated_at' );
		if( ! $deliver->exists() )
			throw new ThreadException( 'DELIVER', 'No deliver found at the moment' );

		return $this->responseInJSON( $deliver->paginate() );
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getMessagesView()
	{
		return view( 'messages.a_messages_delivered' );
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getConversationView()
	{
		return view( 'messages.a_conversation_delivered' );
	}

	/**
	 * @param $id
	 *
	 * @return mixed|\Okie\Deliver
	 */
	public function get( $id )
	{
		if( is_null( Deliver::find( $id ) ) )
			throw new ThreadException( 'DELIVER', 'No deliver found with id ' . $id );

		return $this->responseInJSON( Deliver::find( $id ) );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getConversations( $id )
	{
		if( is_null( Deliver::find( $id ) ) )
			throw new ThreadException( 'DELIVER', 'No deliver found with id ' . $id );

		return $this->responseInJSON( [
			'deliver'       => Deliver::find( $id ),
			'conversations' => Deliver::find( $id )->conversations()->paginate()->toArray()
		] );
	}

	public function getPublicView( $view )
	{
		if( View::exists( 'messages.a_' . $view ) )
			return view( 'messages.a_' . $view );
	}

}
