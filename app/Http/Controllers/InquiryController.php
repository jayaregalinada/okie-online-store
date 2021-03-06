<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\Product;
use Okie\Inquiry;
use Okie\Conversation;
use Okie\Http\Requests;
use Illuminate\Http\Request;
use Okie\Http\Controllers\Controller;
use Okie\Exceptions\ThreadException;
use Okie\Exceptions\ProductException;
use Okie\Services\Inquiry\UploadFactory;
use Okie\Services\Inquiry\ImageProcessor;

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

	/** TODO: PhpDocs */
	public function replyReceipt( Request $request, UploadFactory $factory )
	{
		$directory = "conversations_" . sha1( $request->file( 'file' )->getClientOriginalName() . date( "Y-n-d-His" ) ) . '/';
		$factory->createDirectory( $directory );
		$images = $factory->compileImage( $directory, $request->file( 'file' ), new ImageProcessor );
		$create = $factory->create( [
			'images' => $images,
			'inquiry' => $request->input( 'inquiry' )
		] );

		return $this->responseSuccess( 'Successfully replied with image', $create );
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

	/**
	 * Get all inquiries by Product
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param                          $id
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\ThreadException
	 */
	public function getInquiryByProduct( Request $request, $id )
	{
		$inquiry = Inquiry::whereProductId( $id );
		$product = Product::find( $id );
		if( ! ( $inquiry->count() ) )
			throw new ThreadException( 'INQUIRY', 'No inquiry for that product' );
		if( is_null( $product ) )
			throw new ProductException( 'Product does not exists' );
			
		$this->checkIfAllowed( $inquiry );
		
		return $this->responseSuccess( 'Successfully get all inquiries', [ 
			'product'   => $product,
			'inquiries' => $inquiry->paginate()->toArray()
		] );
	}

	/**
	 * Check if user is allowed
	 *
	 * @param $inquiry
	 *
	 * @throws \Okie\Exceptions\ThreadException
	 */
	protected function checkIfAllowed( $inquiry )
	{
		if( Auth::user()->isUser() && $inquiry->inquisition_id != Auth::id() )
			throw new ThreadException( 'INQUIRY', 'You are not allowed here', 401 );
	}

	/**
	 * Update the inquiry to allow/disallow the receipt upload
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\ThreadException
	 */
	public function toggleUpload( Request $request )
	{
		$inquiry = Inquiry::find( $request->input( 'inquiry' ) );
		if( is_null( $inquiry) )
			throw new ThreadException( 'INQUIRY', 'No inquiry exists' );
		$this->checkIfAllowed( $inquiry );

		$inquiry->update( [
			'uploads' => $request->input( 'uploads' ) 
		] );

		return $this->responseSuccess( 'Successfully update this inquiry to allow receipt uploads', $inquiry );
	}

}
