<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

class MessageStatus extends Model {

	/**
	 * @var string
	 */
	protected $table = 'message_status';

	/**
	 * @var array
	 */
	protected $fillable = [ 'status', 'message_id', 'user_id', 'thread_id', 'type' ];

	/**
	 * @var array
	 */
	public $types = [ 'inquiry', 'inquiry.reply', 'deliver', 'deliver.reply', 'inbox', 'inbox.reply' ];

	/**
	 * @type array
	 */
	public $replyTypes = [ 'inquiry.reply', 'deliver.reply', 'inbox.reply' ];

	/**
	 * @type array
	 */
	public $msgTypes = [ 'inquiry', 'deliver', 'inbox' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'status' => 'integer',
		'message_id' => 'integer',
		'user_id' => 'integer',
		'thread_id' => 'integer'
	];

	/**
	 * Message relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function messages()
	{
		return $this->belongsTo( 'Okie\Message' );
	}

	/**
	 * Scope for counting unread messages
	 *
	 * @param  mixed $query
	 *
	 * @return array        Number of unread messages
	 */
	public function scopeCountUnreadMessages( $query )
	{
		if( $query->whereStatus( 0 )->whereThreadId( null )->get()->isEmpty() )
		{
			return [ 'count' => 0 ];
		}
		else
		{
			return [ 'count' => $query->whereStatus( 0 )->whereThreadId( null )->count() ];
		}
	}

	/**
	 * Scope for counting unread reply messages
	 *
	 * @param  mixed $query
	 * @param  integer $user_id
	 *
	 * @return array          Number of unread reply messages
	 */
	public function scopeCountUnreadReplyMessages( $query, $user_id )
	{
		if( $query->whereStatus( 0 )->whereMessageId( null )->whereUserId( $user_id )->get()->isEmpty() )
		{
			return [ 'count' => 0 ];
		}
		else
		{
			return [ 'count' => $query->whereStatus( 0 )->whereMessageId( null )->whereUserId( $user_id )->count() ];
		}
	}

	/**
	 * User relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo( 'Okie\User' );
	}


	public function scopeGetReplyOnType( $query, $type )
	{
		return $this->replyTypes[ array_search( $type, $this->msgTypes ) ];
	}

}
