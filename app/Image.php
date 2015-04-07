<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

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
