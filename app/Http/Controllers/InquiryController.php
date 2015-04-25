<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\Product;
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
		$inquiry = Inquiry::find( $id );
		if( is_null( $inquiry ) )
			throw new ThreadException( 'INQUIRY', 'No inquiry found with id '. $id );
		$this->checkIfAllowed( $inquiry );

		return $this->responseInJSON( $inquiry );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getConversations( $id )
	{
		$inquiry = Inquiry::find( $id );
		if( is_null( $inquiry ) )
			throw new ThreadException( 'INQUIRY', 'No inquiry found with id '. $id );
		$this->checkIfAllowed( $inquiry );

		return $this->responseInJSON( [
			'inquiry'       => $inquiry,
			'conversations' => $inquiry->conversations()->paginate()->toArray()
		]);
	}

	/**
	 * TODO: [DEPRECATED]
	 * @return \Illuminate\View\View
	 */
	public function getMessagesView()
	{
		return view( 'messages.a_messages_inquiries' );
	}

	/**
	 * TODO: [DEPRECATED]
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
		$this->checkIfAllowed( $inquiry );
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
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\ThreadException
	 */
	public function postReserve( Request $request )
	{
		$inquiry = Inquiry::find( $request->input( 'inquiry' ) );
		if( is_null( $inquiry ) )
			throw new ThreadException( 'INQUIRY', 'Inquiry not found' );
		$this->checkIfAllowed( $inquiry );
		$reserveItem = $inquiry->reserve;
		$inquiry->reserveProduct( $request->input( 'reserve' ) );
		$product = Product::find( $inquiry->product_id );
		if( $request->input( 'reserve' ) > $product->unit )
			throw new ThreadException( 'INQUIRY', 'Cannot reserve item because unit is not enough', 400 );
		$product->update( [
			'unit' => $product->unit - $request->input( 'reserve' )
		] );

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfully reserved', 
			'data' => [
				'inquiry' => Inquiry::find( $request->input( 'inquiry' ) ),
				'product' => Product::find( $inquiry->id )
			],
			'amount' => $request->input( 'reserve' ) ]
		] );
	}

	/** TODO: PhpDocs */
	public function getInquiryByProduct( Request $request, $id )
	{
		$inquiry = Inquiry::whereProductId( $id );
		if( ! ( $inquiry->count() ) )
			throw new ThreadException( 'INQUIRY', 'No inquiry for that product' );
		$this->checkIfAllowed( $inquiry );
		
		return $this->responseSuccess( 'Successfully get all inquiries', $inquiry->paginate()->toArray() );
	}

	/** TODO: PhpDocs */
	protected function checkIfAllowed( $inquiry )
	{
		if( Auth::user()->isUser() && $inquiry->inquisition_id != Auth::id() )
			throw new ThreadException( 'INQUIRY', 'You are not allowed here', 401 );
	}

}
