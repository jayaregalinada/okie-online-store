<?php namespace Okie\Services;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class Response {

	/**
	 * @type \Illuminate\Http\Request
	 */
	private $request;

	/**
	 * @type string
	 */
	private $message;

	/**
	 * @type array
	 */
	private $data;

	/**
	 * @type \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
	 */
	private $response;

	/**
	 * @param string $message
	 * @param array  $data
	 */
	public function __construct( $message = '', $data = [] )
	{
		$this->request  = app( 'request' );
		$this->message  = ( empty( $message ) ? $this->getMessage() : $message );
		$this->data     = ( empty( $data ) ? $this->getData() : $data );
		$this->response = $this->getResponse();
	}

	/**
	 * @param        $content
	 * @param        $status
	 * @param string $callback
	 * @param array  $headers
	 */
	private function response( $content, $status, $callback = 'callback', $headers = [ ] )
	{
		if( $this->request->ajax() || $this->request->wantsJson() )
			$this->setResponse( response()->jsonp( $this->request->input( $callback ), $content, $status, $headers ) );

		$this->setResponse( response( $content, $status, $headers ) );
	}

	/**
	 * @param string $message
	 * @param array  $data
	 * @param int    $status
	 * @param string $callback
	 * @param array  $headers
	 *
	 * @return $this
	 */
	public function success( $message = '', $data = [ ], $status = 200, $callback = 'callback', $headers = [ ] )
	{
		$this->setMessage( $message );
		$this->setData( $data );
		$this->response( [ 'success' => [
			'message' => $this->getMessage(),
			'data'    => $this->getData()
		] ], $status, $callback, $headers );

		return $this;
	}

	/**
	 * @param string $message
	 * @param array  $data
	 * @param int    $status
	 * @param string $callback
	 * @param array  $headers
	 *
	 * @return $this
	 */
	public function error( $message = '', $data = [ ], $status = 404, $callback = 'callback', $headers = [ ] )
	{
		$this->setMessage( $message );
		$this->setData( $data );
		$this->response( [ 'error' => [
			'message' => $this->getMessage(),
			'data' => $this->getData()
		] ], $status, $callback, $headers );

		return $this;
	}

	/**
	 * @return mixed
	 */
	protected function getData()
	{
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	protected function setData( $data )
	{
		$this->data = $data;
	}

	/**
	 * @return \Illuminate\Http\Request
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * @return mixed
	 */
	protected function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param mixed $message
	 */
	protected function setMessage( $message )
	{
		$this->message = $message;
	}

	/**
	 * @return mixed
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * @param mixed $response
	 */
	protected function setResponse( $response )
	{
		$this->response = $response;
	}

	public function prepare()
	{
		return $this->response->prepare( $this->request );
	}

	/**
     * Returns the Response as an HTTP string.
     *
     * The string representation of the Response is the same as the
     * one that will be sent to the client only if the prepare() method
     * has been called before.
     *
     * @return string The Response as an HTTP string
     *
     * @see prepare()
     */
    public function __toString()
    {
	    //return $this->response->prepare();
	    try
	    {
		    header( $this->response->__toString() );
		    return $this->response->__toString();
	    }
	    catch ( \Exception $e )
	    {
		    return $e;
	    }
        //return
        //    sprintf('HTTP/%s %s %s', $this->response->getProtocolVersion(), $this->response->getStatusCode(), $this->response->$statusTexts[ $this->response->getStatusCode() ] )."\r\n".
        //    $this->response->headers."\r\n".
        //    $this->response->getContent();
    }
}
 