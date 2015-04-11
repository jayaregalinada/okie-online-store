<?php namespace Okie\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
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
		if ( $this->auth->guest() )
		{
			$code = 401;
			if ( $request->ajax() )
			{
				$response = [ 'error' => [
					'message'       => 'Unathorized. Please login first',
					'redirect_link' => url( 'auth/login' ),
					'request'       => [
						'url'    => $request->fullUrl(),
						'path'   => $request->path(),
						'method' => $request->method() ]
					]
				];

				return response()->json( $response, $code )->setCallback( $request->input( 'callback' ) );
			}
			else
			{
				return redirect()->guest( 'auth/login' );
			}
		}

		return $next( $request );
	}

}
