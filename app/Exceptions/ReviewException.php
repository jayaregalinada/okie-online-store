<?php namespace Okie\Exceptions;

use Exception;

class ReviewException extends Exception {

	/**
	 * @type array
	 */
	protected $data;

	/**
	 * @type string
	 */
	protected $title;

	/**
	 * @param string $key
	 * @param string $message
	 * @param int    $code
	 * @param string $title
	 * @param array  $data
	 */
	public function __construct( $message = '', $code = 404, $title = 'Opps!', $data = [] )
	{
		$this->data  = $data;
		parent::__construct( $message, $code );
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
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return array
	 */
	public function showInResponse()
	{
		return [
			'error' => [
				'title'     => $this->getTitle(),
				'message'   => $this->getMessage(),
				'code'      => $this->getCode(),
				'exception' => class_basename( get_class( $this ) ),
				'data'      => $this->getData(),
			]
		];
	}

}
