<?php namespace Okie\Exceptions;

use Exception;

class ProductException extends Exception {

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @param null   $message
	 * @param int    $code
	 * @param array  $data
	 */
	public function __construct( $message = null, $code = 404, $title = 'Opps!', $data = [] )
	{
		$this->title = $title;
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
				'data'      => $this->getData()
			]
		];
	}

}
