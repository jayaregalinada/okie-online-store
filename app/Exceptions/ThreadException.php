<?php namespace Okie\Exceptions;

use Exception;

class ThreadException extends Exception {

	/**
	 * @type string
	 */
	protected $type;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @param string $type
	 * @param string $message
	 * @param int    $code
	 * @param string $title
	 * @param array  $data
	 */
	public function __construct( $type = 'GLOBAL', $message = '', $code = 404, $title = 'Opps!', $data = [] )
	{
		$this->type  = $type;
		$this->title = $title;
		$this->data  = $data;
		parent::__construct( '['. $type . '] ' . $message, $code );
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
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
				'title'      => $this->getTitle(),
				'message'    => $this->getMessage(),
				'type'       => $this->getType(),
				'code'       => $this->getCode(),
				'exception'  => class_basename( get_class( $this ) ),
				'data'       => $this->getData(),
				'__toString' => $this->__toString()
			]
		];
	}

}
