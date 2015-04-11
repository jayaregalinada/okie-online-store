<?php namespace Okie\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report( Exception $e )
	{
		return parent::report( $e );
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function render( $request, Exception $e )
	{
		if( $e instanceof ThreadException )
		{
			return response( $e->showInResponse() , $e->getCode() );
		}
		if( $e instanceof NewsletterException )
		{
			return response( $e->showInResponse(), $e->getCode() );
		}
		if( $e instanceof InboxException )
		{
			return response( [ 'error' => [
				'message' => $e->getMessage(),
				'code' => $e->getCode(),
				'type' => $e->getType() ]
			], $e->getCode() );
		}
		// if( $e instanceof \Illuminate\Session\TokenMismatchException )
		// {
		// 	return response( ['error' => [
		// 		'message' => $e->getMessage(),
		// 		'strings' => $e->__toString() ]
		// 	], 500 );
		// }
		return parent::render( $request, $e );
	}

}
