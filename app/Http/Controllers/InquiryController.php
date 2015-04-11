<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\Inquiry;
use Okie\Conversation;
use Okie\Http\Requests;
use Illuminate\Http\Request;
use Okie\Http\Controllers\Controller;
use Okie\Exceptions\ThreadException;

class InquiryController extends Controller {

	// TODO: Create a interface
	/**
	 * Create new instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * @return mixed|\Okie\Inquiry
	 */
	public function getAll()
	{
		$inquiry = Inquiry::latest( 'updated_at' );
		if( Auth::user()->isPermitted() )
		{
			if( ! $inquiry->exists() )
				throw new ThreadException( 'INQUIRY', 'No inquiries found at the moment' );

			return $this->responseInJSON( $inquiry->paginate() );
		}
		else
		{
			if( ! $inquiry->whereInquisitionId( Auth::user()->id )->exists() )
				throw new ThreadException( 'INQUIRY', 'No inquiries found at the moment' );

			return $this->responseInJSON( Inquiry::whereInquisitionId( Auth::user()->id )->latest( 'updated_at' )->paginate() );
		}
	}

	/**
	 * @param $id
	 *
	 * @return mixed|\Okie\Inquiry
	 */
	public function get( $id )
	{
		if( is_null( Inquiry::find( $id ) ) )
			throw new ThreadException( 'INQUIRY', 'No inquiry found with id '. $id );

		return $this->responseInJSON( Inquiry::find( $id ) );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getConversations( $id )
	{
		if( is_null( Inquiry::find( $id ) ) )
			throw new ThreadException( 'INQUIRY', 'No inquiry found with id '. $id );

		return $this->responseInJSON( [
			'inquiry'       => Inquiry::find( $id ),
			'conversations' => Inquiry::find( $id )->conversations()->paginate()->toArray()
		]);
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getMessagesView()
	{
		return view( 'messages.a_messages_inquiries' );
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getConversationView()
	{
		return view( 'messages.a_conversation_inquiry' );
	}

	/**
	 * Reply to Inquiry
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 */
	public function reply( Request $request )
	{
		$inquiry = Inquiry::where( [
			'inquisition_id' => (int) $request->input( 'inquisition' ),
			'product_id'     => (int) $request->input( 'item' )
		] )->first();
		if( is_null( $inquiry ) )
			throw new ThreadException( 'INQUIRY', 'Inquiry not found' );
		$conversation           = new Conversation;
		$conversation->user_id  = Auth::user()->id;
		$conversation->body     = $this->filterBody( $request->input( 'message' ) );
		$conversation->type     = ( Auth::user()->isPermitted() ) ? $conversation->responses[ 'inquiry' ] : 'inquiry';
		$inquiry->conversations()->save( $conversation );

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfully replied',
			'data' => Conversation::find( $conversation->id ) ]
		] );
	}

	/**
	 * TODO: Remove because its deprecated, instead use method @reply()
	 * Reply to inquiry [DEPRECATED]
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 */
	public function postReply( Request $request )
	{
		$inquiry = Inquiry::where( [
			'inquisition_id' => (int) $request->input( 'inquisition' ),
			'product_id'     => (int) $request->input( 'item' )
		] )->first();
		if( is_null( $inquiry ) )
			throw new ThreadException( 'INQUIRY', 'Inquiry not found' );
		$conversation           = new Conversation;
		$conversation->user_id  = Auth::user()->id;
		$conversation->body     = $this->filterBody( $request->input( 'message' ) );
		$conversation->type     = ( Auth::user()->isPermitted() ) ? $conversation->responses[ 'inquiry' ] : 'inquiry';
		$inquiry->conversations()->save( $conversation );

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfully replied',
			'data' => Conversation::find( $conversation->id ) ]
		] );
	}

}
