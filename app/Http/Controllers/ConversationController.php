<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\Product;
use Okie\Conversation;
use Okie\Inbox;
use Okie\Order;
use Okie\Http\Requests;
use Okie\Inquiry;
use Okie\Deliver;
use Illuminate\Http\Request;
use Okie\Http\Controllers\Controller;

class ConversationController extends Controller {

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
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 */
	public function inquireItem( Request $request )
	{
		$product = Product::find( $request->input( 'item') );
		$user = Auth::user();
		$inquiry = Inquiry::firstOrCreate( [
			'inquisition_id' => $user->id,
			'product_id'     => $product->id,
			'title'          => 'Inquiring for ' . $product->name . ' by ' . $user->getFullName()
		] );
		$conversation = new Conversation;
		$conversation->user_id = $user->id;
		$conversation->body = $this->filterBody( $request->input( 'message' ) );
		$inquiry->conversations()->save( $conversation );

		return $this->responseInJSON( [
			'success' => [
				'message' => 'Thank you for inquiring this product. We will get in touch with you soon. Please always check your messages for possible response',
				'data'    => [
					'inquiry'      => $inquiry,
					'conversation' => $conversation
				]
			]
		] );
	}

	

}
