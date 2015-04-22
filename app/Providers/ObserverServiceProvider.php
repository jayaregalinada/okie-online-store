<?php namespace Okie\Providers;

use Illuminate\Support\ServiceProvider;
use Okie\Conversation;
use Okie\Option;

class ObserverServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Conversation::observe( new \Okie\Observers\ConversationObserver );
		// Option::observe( new \Okie\Observers\OptionObserver );
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}
