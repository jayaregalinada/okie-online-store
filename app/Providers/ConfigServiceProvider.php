<?php namespace Okie\Providers;

use DB;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider {

	/**
	 * @type \Illuminate\Database\Query\Builder
	 */
	protected $table;

	/**
	 * @type array
	 */
	protected $config = [];

	/**
	 * All helper variables
	 *
	 * @type array
	 */
	public $helper;

	/**
	 * @return void
	 */
	public function __construct()
	{
		$this->table = DB::table( 'options' );
		$this->helper = [
			'__TITLE__' => config( 'app.title' ),
			'__YEAR__' => date( "Y" ),
		];
		$this->config = $this->changeConfigWithHelpers( $this->table->where( 'type', 'config' )->lists( 'value', 'key' ) );
	}

	/**
	 * @param $string
	 *
	 * @return mixed
	 */
	public function replaceHelpers( $string )
	{
		return str_replace( array_keys( $this->helper ), array_values( $this->helper ), $string );
	}

	/**
	 * @param array $config
	 *
	 * @return array
	 */
	private function changeConfigWithHelpers( array $config )
	{
		foreach( $config as $key => $value )
		{
			$config[ $key ] = ( empty( trim( unserialize( $value ) ) ) ? config( $key ) : $this->replaceHelpers( unserialize( $value ) ) );
		}

		return $config;
	}
	/**
	 * Overwrite any vendor / package configuration.
	 *
	 * This service provider is intended to provide a convenient location for you
	 * to overwrite any "vendor" or package configuration that you may want to
	 * modify before the application handles the incoming request / command.
	 *
	 * @return void
	 */
	public function register()
	{
		config([

		]);
		// config( $this->config );
	}

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	private function getConfig( $key )
	{
		if( ! $this->checkIfExists( $key ) )
			return config( $key );
		else
			return $this->config[ $key ];
	}

	/**
	 * Check if key is exists
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	private function checkIfExists( $key )
	{
		return isset( $this->config[ $key ] );
	}

	public function changeValue( $value )
	{


	}

}
