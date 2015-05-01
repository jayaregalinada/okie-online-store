<?php namespace Okie\Providers;

use Schema;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider {

	/**
	 * @return void
	 */
	public function getDatabaseConfig()
	{
		if( Schema::hasTable( 'options' ) )
		{
			$table = $this->app[ 'db' ]->table( 'options' );

			return $this->changeConfigWithHelpers( $table->where( 'type', 'config' )->lists( 'value', 'key' ) );
		}
	}

	/**
	 * @param $string
	 *
	 * @return mixed
	 */
	public function replaceHelpers( $string )
	{
		$helper = [
			'__TITLE__' => config( 'app.title' ),
			'__YEAR__'  => date( "Y" ),
		];
		return str_replace( array_keys( $helper ), array_values( $helper ), $string );
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
			$config[ $key ] = ( empty( trim( unserialize( base64_decode( $value ) ) ) ) ? config( $key ) : $this->replaceHelpers( unserialize( base64_decode( $value ) ) ) );
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
		config( $this->getDatabaseConfig() );
	}

}
