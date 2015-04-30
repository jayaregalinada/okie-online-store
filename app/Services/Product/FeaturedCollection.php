<?php namespace Okie\Services\Product;

use Illuminate\Database\Eloquent\Collection;
use Okie\Exceptions\ProductException;

class FeaturedCollection extends Collection {

	public function getFeatured( $rating, $operator = '>', $limit, $count )
	{
		$i = [];
		foreach ( $this->items as $key => $value )
		{
			if( ( $this->rating( $value->getRatingAttribute()['average'], $operator, $rating ) && $value->getRatingAttribute()['count'] > $count ) || $value->featured )
				$i[] = $value;
		}
		shuffle( $i );

		if( empty( $i ) )
			return parent::make( [] );
			

		return parent::make( array_slice( $i, 0, $limit ) );
	}

	protected function rating( $value, $operator, $compare )
	{
		if( is_numeric( $value ) )
			switch ( $operator )
			{
				case '>':
					return $value > $compare;
				break;

				case '>=':
					return $value >= $compare;
				break;

				case '<':
					return $value < $compare;
				break;

				case '<=':
					return $value <= $compare;
				break;

				case '=':
					return $value = $compare;
				break;
				
				default:
					return $value = $compare;
				break;
			}
	}

}
