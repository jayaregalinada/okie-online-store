<?php namespace Okie\Exceptions;

use Exception;

class ThreadException extends Exception {

	public function getType()
	{
		return 'danger';
	}

}
