<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

/**
 * Okie\MessageReadState
 *
 */
class MessageReadState extends Model {

	/**
	 * @type string
	 */
	protected $table = 'message_read_state';

	/**
	 * @type array
	 */
	protected $fillable = [ 'message_id', 'user_id' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'message_id' => 'integer',
		'id' => 'integer',
		'user_id' => 'integer'
	];

}
