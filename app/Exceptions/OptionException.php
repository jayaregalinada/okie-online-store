<?php namespace Okie\Exceptions;

use Exception;

class OptionException extends Exception {

	/**
	 * @type array
	 */
	protected $data;

	/**
	 * @type string
	 */
	protected $title;

	/**
	 * @type string
	 */
	protected $key;

	/**
	 * @param string $key
	 * @param string $message
	 * @param int    $code
	 * @param string $title
	 * @param array  $data
	 */
	public function __construct( $key, $message = '', $code = 404, $title = 'Opps!', $data = [] )
	{
		$this->title = $title;
		$this->data  = $data;
		$this->key   = $key;
		parent::__construct( $message . ' [ ' . $key . ' ]', $code );
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
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
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
				'key'       => $this->getKey()
			]
		];
	}

}
