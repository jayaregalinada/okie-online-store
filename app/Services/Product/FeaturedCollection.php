<?php namespace Okie\Services\Product;

use Illuminate\Database\Eloquent\Collection;

class FeaturedCollection extends Collection {

	public function getFeatured( $rating, $operator = '>', $limit )
	{
		$i = [];
		foreach ($this->items as $key => $value)
		{
			if( $this->rating( $value->getRatingAttribute()['average'], $operator, $rating ) || $value->featured )
				$i[] = $value;
		}
		shuffle( $i );

		return array_slice( $i, 0, $limit );
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
