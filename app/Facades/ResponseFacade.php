<?php namespace Okie\Facades;

use Illuminate\Support\Facades\Facade;

class ResponseFacade extends Facade {

	protected static function getFacadeAccessor() { return 'okie.response'; }

}
