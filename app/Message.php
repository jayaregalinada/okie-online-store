<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Okie\Message
 *
 * @property-read \Okie\User $user 
 * @property-read \Okie\Product $product 
 * @property-read mixed $time 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Thread[] $thread 
 * @method static \Okie\Message inquiriesObject()
 * @method static \Okie\Message inquiries()
 * @method static \Okie\Message messagesByProduct($product_id, $user_id)
 */
class Message extends Model {

	use SoftDeletes;

	/**
	 * @var string
	 */
	protected $table = 'messages';

	/**
	 * @var array
	 */
	protected $dates = [ 'deleted_at' ];

	/**
	 * @var array
	 */
	protected $fillable = [ 'user_id', 'body', 'product_id', 'type' ];

	/**
	 * @var array
	 */
	protected $hidden = [ 'updated_at', 'pivot' ];

	/**
	 * @var array
	 */
	protected $appends = [ 'time' ];

	/**
	 * @var array
	 */
	protected $types = [ 'reply', 'inquire', 'message', 'response' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'user_id' => 'integer',
		'product_id' => 'integer'
	];

	/**
	 * @type [type]
	 */
	protected $with = [ 'user' ];

	/**
	 * User relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo( 'Okie\User' );
	}

	/**
	 * Product relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function product()
	{
		return $this->belongsTo( 'Okie\Product' );
	}

	/**
	 * Inquiries object in scope mode
	 *
	 * @param  mixed $query
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeInquiriesObject( $query )
	{
		return $query->whereType( 'inquire' );
	}

	/**
	 * Inquiries in scope mode
	 *
	 * @param  mixed $query
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeInquiries( $query )
	{
		return $query->whereType( 'inquire' )->get();
	}

	/**
	 * Get time attribute
	 *
	 * @return string
	 */
	public function getTimeAttribute()
	{
		if( $this->created_at->diffInSeconds() > 3600 )
			return $this->attributes[ 'time' ] = $this->created_at->toDayDateTimeString();
		
		return $this->attributes[ 'time' ] = $this->created_at->diffForHumans();
	}

	/**
	 * Messages by product in scope mode
	 *
	 * @param  mixed $query
	 * @param  integer $product_id
	 * @param  integer $user_id
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeMessagesByProduct( $query, $product_id, $user_id )
	{
		return $query->whereType( 'inquire' )
					 ->whereProductId( $product_id )
					 ->whereUserId( $user_id )
					 ->latest();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function thread()
	{
		return $this->hasMany( 'Okie\Thread', 'thread_id' );
	}

}
