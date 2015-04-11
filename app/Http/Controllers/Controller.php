<?php namespace Okie\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Request;
use Okie\Services\HTMLSanitizer;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	/**
	 * @param        $data
	 * @param int    $code
	 * @param string $callback
	 *
	 * @return mixed
	 */
	public function responseInJSON( $data, $code = 200, $callback = 'callback' )
	{
		return response()->json( $data, $code )->setCallback( Request::input( $callback ) );
	}

	/**
	 * @param       $strings
	 * @param array $allowedTags
	 *
	 * @return string
	 */
	public function filterBody( $strings, $allowedTags = [ 'p', 'b', 'em', 'img', 'u' ] )
	{
		$sanitizer = new HTMLSanitizer;
		$allowed = $this->filterBodyAllowedTags( $allowedTags );

		return $sanitizer->sanitize( $this->singleBreak( strip_tags( $strings, $allowed ) ) );
	}

	/**
	 * http://stackoverflow.com/questions/133571/how-to-convert-multiple-br-tag-to-a-single-br-tag-in-php
	 *
	 * @param  $strings
	 *
	 * @return string
	 */
	public function singleBreak( $strings )
	{
		return preg_replace( "/(<br\s*\/?>\s*)+/", "<br />", $strings );
	}

	/**
	 * @param array $tags
	 *
	 * @return string
	 */
	private function filterBodyAllowedTags( array $tags = [] )
	{
		array_walk( $tags, function( & $value, $key )
		{
			$value = '<' . $value . '>';
		});
		
		return implode( '', $tags );
	}

}
