<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

/**
 * Okie\Deliver
 *
 * @property integer $id 
 * @property string $title 
 * @property integer $product_id 
 * @property integer $user_id 
 * @property integer $confirm_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Conversation[] $conversations 
 * @property-read \Okie\Product $product 
 * @property-read \Okie\User $user 
 * @property-read \Okie\User $confirm 
 * @method static \Illuminate\Database\Query\Builder|\Okie\Deliver whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Deliver whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Deliver whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Deliver whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Deliver whereConfirmId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Deliver whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Deliver whereUpdatedAt($value)
 */
class Deliver extends Model {

	/**
	 * @type string
	 */
	protected $table = 'thread_delivers';

	/**
	 * @type array
	 */
	protected $with = [ 'user', 'product', 'confirm' ];

	/**
	 * @type array
	 */
	protected $fillable = [ 'title', 'product_id', 'user_id', 'confirm_id' ];

	/**
	 * @type array
	 */
	protected $appends = [ 'latest' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'product_id' => 'integer',
		'user_id' => 'integer',
		'confirm_id' => 'integer'
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
	public function product()
	{
		return $this->belongsTo( 'Okie\Product', 'product_id' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo( 'Okie\User', 'user_id' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function confirm()
	{
		return $this->belongsTo( 'Okie\User', 'confirm_id' );
	}


	/**
	 * @return mixed
	 */
	public function getLatestAttribute()
	{
		return $this->conversations()->getResults()->sortByDesc( 'created_at' )->first();
	}

}
