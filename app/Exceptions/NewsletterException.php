<?php namespace Okie\Exceptions;

use Exception;

class NewsletterException extends Exception {

	/**
	 * @type string
	 */
	protected $email;

	/**
	 * @param string $email
	 * @param string $message
	 * @param int    $code
	 */
	public function __construct( $email = '', $message = '', $code = 400 )
	{
		$this->email = $email;
		parent::__construct( $message, $code );
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @return array
	 */
	public function showInResponse()
	{
		return [
			'error' => [
				'email'     => $this->email,
				'message'   => $this->message,
				'code'      => $this->code,
				'type'      => 'warning',
				'exception' => get_class( $this ),
			]
		];
	}
}
 