<?php namespace Okie\Providers;

use Illuminate\Support\ServiceProvider;
use Okie\Services\Response;

class ResponseServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind( 'okie.response', function()
		{
			return new Response;
		});
	}

}
