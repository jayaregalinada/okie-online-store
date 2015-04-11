<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Okie\Inquiry
 *
 * @property integer $id 
 * @property string $title 
 * @property integer $inquisition_id 
 * @property integer $product_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Conversation[] $conversations 
 * @property-read \Okie\User $user 
 * @property-read \Okie\Product $product 
 * @property-read mixed $latest 
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inquiry whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inquiry whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inquiry whereInquisitionId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inquiry whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inquiry whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Inquiry whereUpdatedAt($value)
 * @method static \Okie\Inquiry checkAlreadyInquired($inquisition, $product)
 * @method static \Okie\Inquiry getInquiriesByProduct($product_id)
 */
class Inquiry extends Model {

	/**
	 * @type string
	 */
	protected $table = 'thread_inquiries';

	/**
	 * Automatic include the inquisition and product
	 *
	 * @type array
	 */
	protected $with = [ 'user', 'product' ];

	/**
	 * @type array
	 */
	protected $fillable = [ 'title', 'inquisition_id', 'product_id' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id'             => 'integer',
		'inquisition_id' => 'integer',
		'product_id'     => 'integer'
	];

	/**
	 * @type array
	 */
	protected $appends = [ 'latest' ];

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
		return $this->belongsTo( 'Okie\User', 'inquisition_id' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function product()
	{
		return $this->belongsTo( 'Okie\Product', 'product_id' );
	}

	/**
	 *
	 * @param $query
	 * @param $inquisition
	 * @param $product
	 *
	 * @return mixed
	 */
	public function scopeCheckAlreadyInquired( $query, $inquisition, $product )
	{
		return $query->whereInquisitionId( $inquisition )->whereProductId( $product )->exists();
	}

	/**
	 * @return mixed
	 */
	public function getLatestAttribute()
	{
		return $this->conversations()->getResults()->sortByDesc( 'created_at' )->first();
	}

	/**
	 * @param $query
	 * @param $product_id
	 *
	 * @return mixed
	 */
	public function scopeGetInquiriesByProduct( $query, $product_id )
	{
		return $query->whereProductId( $product_id )->get();
	}


}
