<?php namespace Okie\Events;

use Illuminate\Queue\SerializesModels;

class MessageEvent extends Event {

	use SerializesModels;

	/**
	 * @var integer
	 */
	public $message_id;

	/**
	 * @var integer
	 */
	public $user_id;

	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var integer
	 */
	public $product_id;

	/**
	 * @var integer
	 */
	public $thread_id;

	/**
	 * @param      $type
	 * @param      $message_id
	 * @param      $user_id
	 * @param null $product_id
	 * @param null $thread_id
	 */
	public function __construct( $type, $message_id, $user_id, $product_id = null, $thread_id = null )
	{
		$this->message_id   = $message_id;
		$this->user_id      = $user_id;
		$this->type         = $type;
		$this->product_id   = $product_id;
		$this->thread_id    = $thread_id;
	}

}
