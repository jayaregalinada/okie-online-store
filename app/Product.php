<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Okie\Product
 *
 * @property integer $id 
 * @property string $name 
 * @property string $code 
 * @property string $description 
 * @property float $price 
 * @property integer $unit 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property integer $user_id 
 * @property integer $thumbnail_id 
 * @property \Carbon\Carbon $deleted_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Image[] $images 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Category[] $categories 
 * @property-read \Okie\User $user 
 * @property-read mixed $thumbnail 
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereUnit($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereThumbnailId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Product whereDeletedAt($value)
 */
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
