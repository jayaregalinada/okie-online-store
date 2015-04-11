<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

/**
 * Okie\Inbox
 *
 * @property integer $id 
 * @property string $title 
 * @property integer $sender_id 
 * @property integer $recipient_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Conversation[] $conversations 
 * @property-read \Okie\User $sender 
 * @property-read \Okie\User $recipient 
 * @property-read mixed $latest 
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inbox whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inbox whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inbox whereSenderId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inbox whereRecipientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inbox whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inbox whereUpdatedAt($value)
 */
class Inbox extends Model {

	/**
	 * @type string
	 */
	protected $table = 'thread_inbox';

	/**
	 * @type array
	 */
	protected $fillable = [ 'title', 'sender_id', 'recipient_id' ];

	protected $with = [ 'sender' ];

	protected $appends = [ 'latest' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'sender_id' => 'integer',
		'recipient_id' => 'integer'
	];
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function conversations()
	{
		return $this->morphMany( 'Okie\Conversation', 'taggable' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function sender()
	{
		return $this->belongsTo( 'Okie\User', 'sender_id' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function recipient()
	{
		return $this->belongsTo( 'Okie\User', 'recipient_id' );
	}

	/**
	 * @return mixed
	 */
	public function getLatestAttribute()
	{
		return $this->conversations()->getResults()->sortByDesc( 'created_at' )->first();
	}

}
