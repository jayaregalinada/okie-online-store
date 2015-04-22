<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model {

	use SoftDeletes;

	/**
	 * @type string
	 */
	protected $table = 'reviews';

	/**
	 * @type array
	 */
	protected $casts = [
		'product_id'    => 'integer',
		'user_id'       => 'integer',
		'id'            => 'integer',
		'rating'        => 'integer'
	];

	/**
	 * @type array
	 */
	protected $appends = [ 'time' ];

	/**
	 * @type array
	 */
	protected $fillable = [ 'product_id', 'user_id', 'message', 'rating', 'approved_by' ];

	/**
	 * @type array
	 */
	protected $with = [ 'user', 'product', 'approved' ];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo( 'Okie\User' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function product()
	{
		return $this->belongsTo( 'Okie\Product' );
	}

	public function approved()
	{
		return $this->belongsTo( 'Okie\User', 'approved_by' );
	}

	/**
	 * @return string
	 */
	public function getTimeAttribute()
	{
		if( $this->updated_at->diffInSeconds() > 3600 )
			return $this->updated_at->toDayDateTimeString();

		return $this->updated_at->diffForHumans();
	}

}
