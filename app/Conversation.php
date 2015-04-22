<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Okie\Conversation
 *
 * @property integer $id 
 * @property string $type 
 * @property integer $user_id 
 * @property string $body 
 * @property integer $taggable_id 
 * @property string $taggable_type 
 * @property string $deleted_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Okie\User $user 
 * @property-read \ $taggable 
 * @property-read \ $inquiry 
 * @property-read mixed $time 
 * @method static \Illuminate\Database\Query\Builder|\Okie\Conversation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Conversation whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Conversation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Conversation whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Conversation whereTaggableId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Conversation whereTaggableType($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Conversation whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Conversation whereUpdatedAt($value)
 */
class Conversation extends Model {

	use SoftDeletes;

	/**
	 * @type string
	 */
	protected $table = 'conversations';

	/**
	 * @type array
	 */
	protected $fillable = [ 'user_id', 'body', 'type' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id'          => 'integer',
		'user_id'     => 'integer',
		'taggable_id' => 'integer'
	];

	/**
	 * @type array
	 */
	protected $types = [
		'Okie\Inquiry' => 'inquiry',
		'Okie\Order'   => 'order',
		'Okie\Deliver' => 'deliver',
		'Okie\Inbox'   => 'inbox'
	];

	/**
	 * @type array
	 */
	public $responses = [
		'inquiry' => 'inquiry-reply',
		'order'   => 'order-reply',
		'deliver' => 'deliver-reply',
		'inbox'   => 'inbox-reply'
	];

	/**
	 * @type array
	 */
	protected $appends = [ 'time' ];

	/**
	 * @type array
	 */
	protected $hidden = [ 'deleted_at', 'updated_at', 'taggable_id', 'taggable_type' ];

	/**
	 * @type array
	 */
	protected $with = [ 'user' ];

	/**
	 * @type int
	 */
	protected $perPage = 20;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo( 'Okie\User' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function taggable()
	{
		return $this->morphTo();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function inquiry()
	{
		return $this->morphTo();
	}

	/**
	 * @return mixed|string
	 */
	public function getTimeAttribute( )
	{
		if( $this->created_at->diffInSeconds() > 3600 )
			return $this->created_at->toDayDateTimeString();

		return $this->created_at->diffForHumans();
	}

}
