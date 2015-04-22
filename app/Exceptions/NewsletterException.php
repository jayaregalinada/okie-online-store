<?php namespace Okie\Exceptions;

use Exception;

class NewsletterException extends Exception {

	/**
	 * @type string
	 */
	protected $email;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @param string $email
	 * @param string $message
	 * @param int    $code
	 */
	public function __construct( $email = '', $message = '', $code = 400, $title = 'Opps :(' )
	{
		$this->email = $email;
		$this->title = $title;
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
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return array
	 */
	public function showInResponse()
	{
		return [
			'error' => [
				'title'     => $this->getTitle(),
				'email'     => $this->getEmail(),
				'message'   => $this->getMessage(),
				'code'      => $this->getCode(),
				'type'      => 'warning',
				'exception' => class_basename( get_class( $this ) ),
			]
		];
	}
}
 
