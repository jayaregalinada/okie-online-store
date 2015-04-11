<?php namespace Okie\Providers;

use Okie\Inquiry;
use Okie\Conversation;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Inquiry::observe( new \Okie\Observers\InquiryObserver );
		Conversation::observe( new \Okie\Observers\ConversationObserver );
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
