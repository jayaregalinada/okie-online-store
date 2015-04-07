<?php namespace Okie\Handlers\Events;

use Auth;
use Okie\Message;
use Okie\Thread;
use Okie\MessageStatus;
use Okie\Events\MessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class UnreadMessagesHandler {

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
				return $this->addMessageStatus( $event->message_id, $event->user_id, 'inquiry', $event->thread_id );
			break;

			case "reply":
				return $this->replyMessageStatus( $event->thread_id, $event->user_id );
			break;

			case "message":
				return $this->addMessageStatusInbox( $event->message_id, $event->user_id, $event->thread_id );
			break;
		}
	}

	/**
	 * Add a message status
	 *
	 * @param  integer $message_id
	 * @param  integer $user_id
	 * @param  string  $type
	 * @param  null    $thread_id
	 *
	 * @return \Okie\MessageStatus
	 */
	private function addMessageStatus( $message_id, $user_id, $type = 'inquiry', $thread_id = null )
	{
		return MessageStatus::create([
			'type'       => $type,
			'status'     => 0,
			'message_id' => $message_id,
			'thread_id'  => $thread_id,
			'user_id'    => $user_id,
		]);
	}

	private function addMessageStatusInbox( $message_id, $user_id, $thread_id )
	{
		return MessageStatus::create([
			'type'       => 'inbox',
			'status'     => 0,
			'message_id' => $message_id,
			'user_id'    => ( $thread_id ? $thread_id : $user_id )
		]);
	}

	/**
	 * @param        $thread_id
	 * @param        $user_id
	 *
	 * @return \Okie\MessageStatus
	 */
	private function replyMessageStatus( $thread_id, $user_id )
	{
		return MessageStatus::create([
			'type'      => MessageStatus::getReplyOnType( Thread::find( $thread_id )->type ),
			'status'    => 0,
			'thread_id' => $thread_id,
			'user_id'   => ( Auth::user()->isAdmin() ? $thread_id : Thread::find( $thread_id )->user_id )
		]);
	}

}
