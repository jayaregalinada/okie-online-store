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
	protected $fillable = [ 'name', 'description', 'navigation', 'slug', 'parent_id' ];

	/**
	 * @var array
	 */
	protected $hidden = [ 'pivot', 'created_at', 'updated_at', 'parent_id' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'navigation' => 'boolean',
		'parent_id' => 'integer',
		'is_mother' => 'boolean'
	];

	/**
	 * @type array
	 */
	protected $appends = [ 'parent', 'children', 'parent_info', 'is_mother' ];

	/**
	 * Products relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function products()
	{
		return $this->belongsToMany( 'Okie\Product', 'product_category' );
	}

	/**
	 * Parent relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parent()
	{
		return $this->belongsTo( 'Okie\Category', 'parent_id' );
	}

	/**
	 * @return bool|array
	 */
	public function getParentAttribute()
	{
		if( $this->attributes[ 'parent_id' ] == $this->id )
			return true;

		if( !( $this->parent()->getResults()[ 'id' ] ) )
		{
			return false;
		}
		else
		{
			return [
				'id' => $this->parent()->getResults()[ 'id' ],
				'name' => $this->parent()->getResults()[ 'name' ],
				'description' => $this->parent()->getResults()[ 'description' ],
				'slug' => $this->parent()->getResults()[ 'slug' ],
			];
		}
	}

	/**
	 * @return mixed
	 */
	public function getChildrenAttribute()
	{
		if( $this->parent_id != $this->id )
			return null;

		return $this->where( 'parent_id', '=', $this->parent_id )->where( 'id', '!=', $this->parent_id )->getQuery()->get();
	}

	public function getChildren()
	{
		return $this->where( 'parent_id', '=', $this->parent_id )->where( 'id', '!=', $this->parent_id );
	}

	/**
	 * @return array
	 */
	public function getParentInfoAttribute()
	{
		if( is_null( $this->parent()->getResults()[ 'id' ] ) )
			return null;

		return [
			'id' => $this->parent()->getResults()[ 'id' ],
			'name' => $this->parent()->getResults()[ 'name' ],
			'description' => $this->parent()->getResults()[ 'description' ],
			'slug' => $this->parent()->getResults()[ 'slug' ],
		];
	}

	public function isMother()
	{
		return (bool) ( $this->id === $this->parent_id );
	}

	public function getIsMotherAttribute()
	{
		return $this->isMother();
	}

}
