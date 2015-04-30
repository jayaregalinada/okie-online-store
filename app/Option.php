<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;
use Okie\Exceptions\OptionException;

class Option extends Model {

	/**
	 * @type array
	 */
	protected $fillable = [ 'type', 'key', 'value' ];

	/**
	 * @type array
	 */
	protected $hidden = [ 'updated_at', 'created_at' ];

	/**
	 * @type string
	 */
	protected $table = 'options';

	/**
	 * @param $value
	 *
	 * @return null|string
	 */
	public function setValueAttribute( $value )
	{
		if( is_null( $value ) )
			return $this->attributes[ 'value' ] = null;
		else
			return $this->attributes[ 'value' ] = base64_encode( serialize( $value ) );
	}

	/**
	 * @param $value
	 *
	 * @return mixed|null
	 */
	public function getValueAttribute( $value )
	{
		if( is_null( $value ) )
			return null;

		$value = unserialize( base64_decode( $value ) );
		if( is_array( json_decode( $value ) ) )
			return json_decode( $value );

		return $value;
	}

	/**
	 * @param      $query
	 * @param      $key
	 * @param null $default
	 *
	 * @throws \Okie\Exceptions\OptionException
	 */
	public function scopeGetValue( $query, $key, $default = null )
	{
		if( ! is_null( $default ) )
		{
			$find = $query->whereKey( $key );
			if( ! $find->exists() )
			{
				return $default;
			}
			else
			{
				return $find->first();
			}
		}
		else
		{
			throw new OptionException( $key, 'No Key Exists' );
		}
	}

}
