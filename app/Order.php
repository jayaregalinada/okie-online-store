<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

/**
 * Okie\Order
 *
 * @property integer $id 
 * @property integer $product_id 
 * @property integer $user_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Conversation[] $conversations 
 * @property-read \Okie\User $user 
 * @property-read \Okie\Product $product 
 * @method static \Illuminate\Database\Query\Builder|\Okie\Order whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Order whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Order whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Order whereUpdatedAt($value)
 */
class Order extends Model {

	/**
	 * @type string
	 */
	protected $table = 'thread_orders';

	/**
	 * @type array
	 */
	protected $fillable = [ 'title', 'product_id', 'user_id' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'product_id' => 'integer',
		'user_id' => 'integer'
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
	public function user()
	{
		return $this->belongsTo( 'Okie\User', 'user_id' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function product()
	{
		return $this->belongsTo( 'Okie\Product', 'product_id' );
	}
}
