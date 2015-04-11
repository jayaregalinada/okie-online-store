<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

/**
 * Okie\Category
 *
 * @property integer $id 
 * @property string $name 
 * @property string $description 
 * @property string $slug 
 * @property boolean $navigation 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Product[] $products 
 * @method static \Illuminate\Database\Query\Builder|\Okie\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Category whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Category whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Category whereNavigation($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\Category whereUpdatedAt($value)
 */
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
