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
		switch ( true )
		{
			case $e instanceof ThreadException:
			case $e instanceof NewsletterException:
			case $e instanceof ProductException:
			case $e instanceof UserException:
			case $e instanceof OptionException:
			case $e instanceof ReviewException:
				if( $request->wantsJson() || $request->ajax() )
					return response()->json( $e->showInResponse(), $e->getCode() )->setCallback( $request->input( 'callback' ) );
				if( $e->getCode() == 404 )
					return abort( 404, $e->getMessage() );
				
				if( ! app()->environment( 'local' ) )
				{
					if( config( 'app.debug' ) )
						return $this->renderExceptionWithWhoops( $request, $e );
					
					return parent::render( $request, $e );
				}
				else
				{
					return response( $e->showInResponse(), $e->getCode() );
				}
			break;
			
			default:
				if( ! app()->environment( 'local' ) )
				{
					$response = [
						'error' => [
							'message' => $e->getMessage(),
							'exception' => class_basename( $e ),
							'code' => $e->getCode(),
							'line' => $e->getLine(),
							'trace_string' => $e->getTraceAsString(),
							'trace' => $e->getTrace(),
							'previous' => $e->getPrevious(),
							'file' => class_basename( $e->getFile() )
						]
					];
					if( $request->wantsJson() || $request->ajax() )
						return response()->json( $response, $e->getStatusCode() )->setCallback( $request->input( 'callback' ) );
					if( config( 'app.debug' ) )
						return $this->renderExceptionWithWhoops( $request, $e );
					
					return parent::render( $request, $e );
				}
				
				if( config( 'app.debug' ) )
					return $this->renderExceptionWithWhoops( $request, $e );
				
				return parent::render( $request, $e );
			break;
		}
	}

	/**
	 * Render an exception using Whoops.
	 * [https://mattstauffer.co/blog/bringing-whoops-back-to-laravel-5]
	 * 
	 * @param  \Exception $e
	 * @return \Illuminate\Http\Response
	 */
	protected function renderExceptionWithWhoops( $request, Exception $e )
	{
		$whoops = new \Whoops\Run;
		if( $request->ajax() || $request->wantsJson() )
		{
			$whoops->pushHandler( new \Whoops\Handler\JsonResponseHandler() );
			return response( $whoops->handleException($e), $e->getStatusCode(), $e->getHeaders() );
		}
		else
		{
			$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
			return response( $whoops->handleException($e), $e->getStatusCode(), $e->getHeaders() );
		}
	}

}
