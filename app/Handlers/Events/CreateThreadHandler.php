<?php
namespace Okie\Handlers\Events;

use Auth;
use Okie\User;
use Okie\Thread;
use Okie\Message;
use Okie\Product;
use Okie\Exceptions\ThreadException;
use Okie\Events\MessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class CreateThreadHandler {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  MessageEvent  $event
	 * 
	 * @return void
	 */
	public function handle( MessageEvent $event )
	{
		switch ( $event->type )
		{
			case "inquire":
			if( is_null( $event->thread_id ) )
			{
				return $this->createThreadForInquiry( $event );
			}
			else
			{
				if( Thread::find( $event->thread_id )->isDelivered() )
					throw new ThreadException( "Thread is already delivered", 400 );

				Thread::find( $event->thread_id )->touch();
				return Thread::find( $event->thread_id )->messages()->attach( [ 'id' => $event->message_id ] );
			}
			break;

			case "reply":
			return $this->replyToThread( $event->thread_id, $event->message_id );
			break;

			case "message":
				return $this->createThreadForInbox( $event );
			break;
		}
	}

	/**
	 * Attached a message in thread when reply
	 *
	 * @param  integer $id
	 * @param  integer $message_id
	 *
	 * @return \Okie\Thread
	 */
	private function replyToThread( $id, $message_id )
	{
		Thread::find( $id )->touch();
		return Thread::find( $id )->messages()->attach( [ 'id' => $message_id ] );
	}

	/**
	 * Create a thread for inquiry
	 * This will check if thread is exist
	 *
	 * @param  \Okie\Events\MessageEvent $event
	 *
	 * @return \Okie\Thread
	 */
	private function createThreadForInquiry( $event )
	{
		if( $this->checkThread( $event->user_id, $event->product_id ) )
			return $this->syncItsExistes( $event->message_id, $event->product_id, $event->user_id );

		return $this->createThread( $event->user_id, $event->message_id, $event->product_id, $event->type );
	}

	/**
	 * Check if thread exists using thread id
	 *
	 * @param  integer $id
	 *
	 * @return boolean
	 */
	protected function checkThreadIfExists( $id )
	{
		$find = Thread::find( $id );

		return $find->exists();
	}

	/**
	 * Check thread if exists
	 *
	 * @param  integer $user_id
	 * @param  integer $product_id
	 *
	 * @return boolean
	 */
	private function checkThread( $user_id, $product_id )
	{
		$find = Thread::checkInquiryThreads( $product_id, $user_id );

		return $find->exists();
	}

	/**
	 * Create a thread
	 *
	 * @param  integer $user_id
	 * @param  integer $message_id
	 * @param  integer $product_id
	 * @param  string $type
	 *
	 * @return \Okie\Thread
	 */
	protected function createThread( $user_id, $message_id, $product_id = null, $type = 'inquiry' )
	{
		$user = User::find( $user_id );
		$product = Product::find( $product_id );

		return Thread::create([
			'name' => 'Inquiring for ' . $product->name . ' by ' . $user->first_name . ' ' . $user->last_name,
			'type' => $type,
			'user_id' => $user_id,
			'product_id' => $product_id
		])->messages()->attach( [ 'id' => $message_id ] );
	}

	/**
	 * Check if thread is exists
	 *
	 * @param  integer $message_id
	 * @param  integer $product_id
	 * @param  integer $user_id
	 *
	 * @return \Okie\Thread
	 */
	protected function syncItsExistes( $message_id, $product_id, $user_id )
	{
		$find = Thread::checkInquiryThreads( $product_id, $user_id )->first()->id;
		Thread::find( $find )->touch();

		return Thread::find( $find )->messages()->attach( [ 'id' => $message_id ] );
	}

	/**
	 * Create thread for inbox
	 *
	 * @param  object $event
	 *
	 * @return static|\Okie\Thread
	 */
	protected function createThreadForInbox( $event )
	{
		$name = ( empty( $event->product_id ) ? 'Message from ' . Auth::user()->getFullName() : $event->product_id );
		$thread = Thread::create([
			'name' => $name,
			'type' => 'inbox',
			'user_id' => ( Auth::user()->isAdmin() ? $event->thread_id : $event->user_id ),
		]);
		$thread->messages()->attach( [ 'id' => $event->message_id ] );

		return $thread;
	}

}
