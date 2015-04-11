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
	 * @param string $type
	 * @param null   $message
	 * @param int    $code
	 * @param array  $data
	 */
	public function __construct( $type = 'GLOBAL', $message = null, $code = 404, $data = [] )
	{
		$this->type = $type;
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
	 * @return array
	 */
	public function showInResponse()
	{
		return [
			'error' => [
				'message'   => $this->message,
				'type'      => $this->type,
				'code'      => $this->code,
				'exception' => get_class( $this ),
				'data'      => $this->data
			]
		];
	}

}
