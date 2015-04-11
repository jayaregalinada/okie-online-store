<?php namespace Okie\Exceptions;

use Exception;

class InboxException extends Exception {

	public function getType()
	{
		return 'danger';
	}

}
