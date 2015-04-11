<?php namespace Okie\Http\Controllers;
// TODO: This is DEPRECATED
use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Okie\Message;
use Auth, Event;
use Okie\Events\MessageEvent;
use Okie\Thread;
use Illuminate\Http\Request;

class MessageController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * Inquiring an item
	 *
	 * @param  Request $request
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory
	 */
	public function inquireItem( Request $request )
	{
		$message = Message::create([
			'user_id' => Auth::user()->id,
			'product_id' => $request->input( 'item' ),
			'body' => $this->filterBody( $request->input( 'message' ) ),
			'type' => 'inquire'
		]);
		Event::fire( new MessageEvent( 'inquire', $message->id, Auth::user()->id, $message->product_id ) );

		return response()->json([ 'success' => [
			'message' => 'Thank you for inquiring. We will message you afterwards.',
			'data' => $message ]
		]);
	}

	/**
	 * Reply to an inquiry
	 *
	 * @param  Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function replyToInquire( Request $request )
	{
		if( Auth::user()->isAdmin() )
		{
			if( is_null( $request->input( 'item' ) ) )
			{
				$type = 'message';
			}
			else
			{
				$type = 'reply';
			}
		}
		else
		{
			$type = 'inquire';
		}
		// $type = ( Auth::user()->isAdmin() ) ? is_null( $request->input( 'item' ) ? 'message' : 'reply' : 'inquire';
		$message = Message::create([
			'user_id' => Auth::user()->id,
			'product_id' => $request->input( 'item' ),
			'body' => $this->filterBody( $request->input( 'reply' ) ),
			'type' => $type
		]);

		Event::fire( new MessageEvent( $type, $message->id, Auth::user()->id, $message->product_id, $request->input( 'thread' ) ) );

		return response()->json([ 'success' => [
			'message' => 'Successfully replied',
			'data' => $message ]
		]);
	}

	/**
	 * Get index
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view( 'messages.index' );
	}

	/**
	 * Get Messages view
	 *
	 * @return \Illuminate\View\View
	 */
	public function getMessagesView()
	{
		return view( 'messages.a_messages' );
	}

	/**
	 * Get Conversation view
	 *
	 * @return \Illuminate\View\View
	 */
	public function getConversationView()
	{
		return view( 'messages.a_conversation' );
	}

	/**
	 * Get Conversation delivered view
	 *
	 * @return \Illuminate\View\View
	 */
	public function getConversationDeliveredView()
	{
		return view( 'messages.a_conversation_delivered' );
	}

	/**
	 * Get Create view
	 *
	 * @return \Illuminate\View\View
	 */
	public function getCreateView()
	{
		return view( 'messages.a_create' );
	}

	/**
	 * Get Inbox view
	 *
	 * @return \Illuminate\View\View
	 */
	public function getInboxView()
	{
		return view( 'messages.a_inbox' );
	}

	/**
	 * Get Conversation inbox view
	 *
	 * @return \Illuminate\View\View
	 */
	public function getConversationInboxView()
	{
		return view( 'messages.a_conversation_inbox' );
	}

	/**
	 * Get all inquiries
	 *
	 * @return \Okie\Message
	 */
	public function getAllInquiries()
	{
		return Message::with( 'product' )->inquiriesObject()->orderBy('created_at', 'desc')->paginate();
	}

	/**
	 * Get Messages by Product
	 *
	 * @param  Request $request
	 * @param  integer  $product_id
	 *
	 * @return \Okie\Message
	 */
	public function getMessagesByProduct( Request $request, $product_id )
	{
		return Message::with( 'product' )->messagesByProduct( $product_id, $request->input( 'user' ) )->paginate();
	}

	/**
	 * Create a message
	 *
	 * @param  Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function createMessage( Request $request )
	{
		$message = Message::create([
			'user_id' => Auth::user()->id,
			'body' => $this->filterBody( $request->input( 'body' ) ),
			'type' => 'message'
		]);
		$event = Event::fire( new MessageEvent( $message->type, $message->id, Auth::user()->id, $request->input( 'subject' ), $request->input( 'user' ) ) );

		return response()->json([
			'success' => [
				'message' => 'Successfully message sent',
				'data' => [
					'message' => $message,
					'thread' => $event[1]
				]
			]
		]);
	}



}
