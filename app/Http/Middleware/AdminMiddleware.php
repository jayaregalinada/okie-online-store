<?php namespace Okie\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AdminMiddleware {

	/**
	 * @var \Illuminate\Contracts\Auth\Guard
	 */
	protected $auth;

	/**
	 * Create new instance
	 *
	 * @param Guard $auth
	 * 
	 * @return void
	 */
	public function __construct( Guard $auth )
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * 
	 * @return mixed
	 */
	public function handle( $request, Closure $next )
	{
		if( ! $this->auth->user()->isPermitted() )
		{
			$code = 401;
			if ( $request->ajax() )
			{
				$response = [ 'error' => [
					'message' => 'Sorry only admin allow on this page',
					'code' => $code,
					'request' => [
						'url' => $request->fullUrl(),
						'path' => $request->path(),
						'method' => $request->method() ]
					]
				];

				return response()->json( $response, $code )->setCallback( $request->input( 'callback' ) );
			}
			else
			{
				return abort( $code, 'Sorry only admin allow on this page' );
			}
		}

		return $next( $request );
	}

}
