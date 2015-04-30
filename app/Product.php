<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Okie\Services\Product\FeaturedCollection;

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
	protected $fillable = [ 'name', 'code', 'description', 'price', 'unit', 'user_id', 'thumbnail_id', 'badge', 'sale_price', 'featured' ];

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
	protected $appends = [ 'thumbnail', 'rating' ];

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
		'thumbnail_id' => 'integer',
		'featured' => 'boolean'
	];

	/**
	 * Possible object key for badge column
	 * 
	 * @type array
	 */
	public $badgeAttributes = [
		'title', 'description', 'slug', 'class', 'class_array'
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
		if( is_null( $this->getThumbnail()['sizes'] ) )
			return $this->defaultThumbnail();

		return $this->getThumbnail()['sizes'];
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function reviews()
	{
		return $this->hasMany( 'Okie\Review' );
	}

	/**
	 * @param $query
	 *
	 * @return float
	 */
	public function scopeCalculateRating( $query )
	{
		$reviews = $this->reviews()->whereNotNull( 'approved_by' );
		$avgRating = $reviews->avg( 'rating' );

		return round( $avgRating, 1 );
	}

	/**
	 * @return array
	 */
	public function getRatingAttribute()
	{
		return [
			'average' => $this->calculateRating(),
			'count'   => $this->reviews()->whereNotNull( 'approved_by' )->count()
		];
	}

	/**
	 * If product do not have any thumbnail
	 *
	 * @return array
	 */
	private function defaultThumbnail()
	{
		return [
			[
				'width' => 600,
				'height' => 600,
				'url' => url( '/images/defaults/product_org.jpg' ),
				'base_dir' => '/images/defaults/product_org.jpg'
			],
			[
				'width' => 50,
				'height' => 50,
				'url' => url( '/images/defaults/product_sqr.jpg' ),
				'base_dir' => '/images/defaults/product_sqr.jpg'
			],
			[
				'width' => 280,
				'height' => 280,
				'url' => url( '/images/defaults/product_thn.jpg' ),
				'base_dir' => '/images/defaults/product_thn.jpg'
			],
			[
				'width' => 150,
				'height' => 150,
				'url' => url( '/images/defaults/product_sml.jpg' ),
				'base_dir' => '/images/defaults/product_sml.jpg'
			],
			[
				'width' => 300,
				'height' => 300,
				'url' => url( '/images/defaults/product_mdm.jpg' ),
				'base_dir' => '/images/defaults/product_mdm.jpg'
			],
			[
				'width' => 600,
				'height' => 600,
				'url' => url( '/images/defaults/product_lrg.jpg' ),
				'base_dir' => '/images/defaults/product_lrg.jpg'
			]
		];
	}

	/**
	 * @param $value
	 *
	 * @return null|string
	 */
	public function setBadgeAttribute( $value )
	{
		if( is_null( $value ) )
			return $this->attributes[ 'badge' ] = null;
		else
			return $this->attributes[ 'badge' ] = serialize( $value );
	}

	/**
	 * @param $value
	 *
	 * @return array|mixed
	 */
	public function getBadgeAttribute( $value )
	{
		if( ! ( $this->attributes[ 'badge' ] ) )
			return [
				'class' => 'ribbon-default',
				'class_array' => [ 'ribbon-default' ]
			];

		return unserialize( $value );
	}

	/**
	 * @param array $data
	 *
	 * @return bool|int
	 */
	public function editBadge( array $data )
	{
		return $this->update( [
			'badge' => [
				'title' => $data[ 'title' ],
				'description' => $data[ 'description' ],
				'slug' => str_slug( $data[ 'title' ] ),
				'class' => $data[ 'class' ],
				'class_array' => explode( ' ', $data[ 'class' ] )
			]
		] );
	}

	/**
	 * @return bool|int
	 */
	public function destroyBadge()
	{
		return $this->update( [
			'badge' => null ]
		);
	}

	/**
	 * @param     $query
	 * @param     $id
	 * @param int $limit
	 *
	 * @return mixed
	 */
	public function scopeGetRelated( $query, $id, $limit = 5 )
	{
		return $query->where( 'id', '!=', $id )->orderByRaw( "RAND()" )->limit( $limit )->get();
	}

	public function scopeGetFeatured( $query, $limit = 3, $rating = null, $operator = null, $count = null )
	{
		$rating   = ( $rating ) ? $rating : config( 'product.featured.rating' );
		$operator = ( $operator ) ? $operator : config( 'product.featured.operator' );
		$count    = ( $count ) ? $count : config( 'product.featured.count' );
		return ( new FeaturedCollection( $this->all() ) )->getFeatured( $rating, $operator, $limit, $count );
	}

}
