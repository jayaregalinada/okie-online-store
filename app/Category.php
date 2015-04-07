<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

	/**
	 * @var string
	 */
	protected $table = 'categories';

	/**
	 * @var array
	 */
	protected $fillable = [ 'name', 'description', 'navigation' ];

	/**
	 * @var array
	 */
	protected $hidden = [ 'pivot', 'created_at', 'updated_at' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'navigation' => 'boolean'
	];

	/**
	 * Products relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function products()
	{
		return $this->belongsToMany( 'Okie\Product', 'product_category' );
	}

}
