<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;


class Thread extends Model {

	/**
	 * @var string
	 */
	protected $table = 'threads';

	/**
	 * @var array
	 */
	protected $fillable = [ 'title', 'user_id', 'product_id', 'type' ];

	/**
	 * @var array
	 */
	protected $appends = [ 'latest' ];

	/**
	 * 
	 * Different types of tread
	 * 
	 * @var array
	 */
	protected $types = [ 'inquiry', 'deliver', 'inbox' ];

	/**
	 * @var integer
	 */
	protected $perPage = 5;

	/**
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'product_id' => 'integer',
		'user_id' => 'integer'
	];

	/**
	 * Messages relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function messages()
	{
		return $this->belongsToMany( 'Okie\Message' );
	}

	/**
	 * User relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo( 'Okie\User', 'user_id' );
	}

	/**
	 * Product relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function product()
	{
		return $this->belongsTo( 'Okie\Product', 'product_id' );
	}

	/**
	 * Check inquiry threads by checking product id and user id
	 *
	 * @param  mixed $query
	 * @param  integer $product_id
	 * @param  integer $user_id
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeCheckInquiryThreads( $query, $product_id, $user_id )
	{
		return $query->whereType( 'inquire' )
					 ->whereProductId( $product_id )
					 ->whereUserId( $user_id )
					 ->orderBy( 'updated_at', 'desc' );
	}

	/**
	 * Get latest message in thread
	 *
	 * @return string
	 */
	public function getLatestAttribute()
	{
		return $this->attributes[ 'latest' ] = $this->messages->first();
	}

	/**
	 * Check if thread is a delivered
	 *
	 * @return boolean
	 */
	public function isDelivered()
	{
		return $this->attributes[ 'type' ] == $this->types[ 1 ];
	}

	/**
	 * Check if thread is a inquiry
	 *
	 * @return boolean
	 */
	public function isInquiry()
	{
		return $this->attributes[ 'type' ] == $this->types[ 0 ];
	}



}
