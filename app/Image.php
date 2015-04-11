<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

/**
 * Okie\Image
 *
 * @property integer $id 
 * @property string $sizes 
 * @property integer $product_id 
 * @property string $caption 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Okie\Product $product 
 * @method static \Illuminate\Database\Query\Builder|\Okie\Image whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Image whereSizes($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Image whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Image whereCaption($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Image whereUpdatedAt($value)
 */
class Image extends Model {

	/**
	 * @var string
	 */
	protected $table = 'images';

	/**
	 * @var array
	 */
	protected $fillable = [ 'sizes', 'product_id', 'caption' ];

	/**
	 * @var array
	 */
	protected $hidden = [ 'product_id', 'created_at', 'updated_at' ];

	/**
	 * @var array
	 */
	protected $touches = [ 'product' ];

	/**
	 * This will return data type when HHVM
	 *
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'product_id' => 'integer'
	];

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
	 * Change the sizes attribute into JSON or Array
	 *
	 * @param  object $value
	 *
	 * @return array
	 */
	public function getSizesAttribute( $value )
	{
		$j = json_decode( $value, true );
		foreach ( $j as $key => $value )
		{
			$j[ $key ][ 'url' ] = url( $j[ $key ]['url'] );
		}
		
		return $j;
	}

}
