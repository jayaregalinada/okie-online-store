<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {

	use SoftDeletes;

	/**
	 * @var string
	 */
	protected $table = 'products';

	/**
	 * @var array
	 */
	protected $fillable = [ 'name', 'code', 'description', 'price', 'unit', 'user_id', 'thumbnail_id' ];

	/**
	 * @var array
	 */
	protected $dates = [ 'deleted_at' ];

	/**
	 * @var array
	 */
	protected $hidden = [ 'user_id', 'created_at', 'deleted_at', 'thumbnail_id' ];

	/**
	 * @var integer
	 */
	protected $perPage = 15;

	/**
	 * @var array
	 */
	protected $appends = [ 'thumbnail' ];

	/**
	 * @var array
	 */
	protected $with = [ 'categories', 'images' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'unit' => 'integer',
		'user_id' => 'integer',
		'thumbnail_id' => 'integer'
	];

	/**
	 * Images relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function images()
	{
		return $this->hasMany( 'Okie\Image' );
	}

	/**
	 * Categories relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function categories()
	{
		return $this->belongsToMany( 'Okie\Category', 'product_category' );
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

	/**
	 * Get the Thumbnail
	 * Check if in column `thumbnail_id` exists in images table
	 *
	 * @return object|\Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getThumbnail()
	{
		if( $this->images()->whereId( $this->thumbnail_id )->exists() )
			return $this->images()->whereId( $this->thumbnail_id )->first();

		return $this->images->first();
	}

	/**
	 * Attributes for `thumbnail`
	 *
	 * @return array
	 */
	public function getThumbnailAttribute()
	{
		return $this->attributes['thumbnail'] = $this->getThumbnail()['sizes'];
	}

}
