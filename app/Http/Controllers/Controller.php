<?php namespace Okie\Http\Controllers;

use Request;
use Okie\Services\HTMLSanitizer;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	/**
	 * @return array
	 */
	public $smileys = [
		// SMILEYS
		'&lt;3'                => '<i class="fa fa-heart"></i>',
		':heart'               => '<i class="fa fa-heart"></i>',
		':like'                => '<i class="fa fa-thumbs-up"></i>',
		':thumbsup:'           => '<i class="fontelico-emo-thumbsup"></i>',
		'(y)'                  => '<i class="fontelico-emo-thumbsup"></i>',
		':boo'                 => '<i class="fa fa-thumbs-down"></i>',
		':)'                   => '<i class="fontelico-emo-happy"></i>',
		':/'                   => '<i class="fontelico-emo-displeased"></i>',
		'>:)'                  => '<i class="fontelico-emo-devil"></i>',
		'}:-)'                 => '<i class="fontelico-emo-devil"></i>',
		':_('                  => '<i class="fontelico-emo-cry"></i>',
		':\'('                 => '<i class="fontelico-emo-cry"></i>',
		'T_T'                  => '<i class="fontelico-emo-cry"></i>',
		';_;'                  => '<i class="fontelico-emo-cry"></i>',
		':-D'                  => '<i class="fontelico-emo-grin"></i>',
		':D'                   => '<i class="fontelico-emo-grin"></i>',
		'XD'                   => '<i class="fontelico-emo-laugh"></i>',
		':))'                  => '<i class="fontelico-emo-laugh"></i>',
		'0:-)'                 => '<i class="fontelico-emo-saint"></i>',
		'O:-)'                 => '<i class="fontelico-emo-saint"></i>',
		'0=)'                  => '<i class="fontelico-emo-saint"></i>',
		'O=)'                  => '<i class="fontelico-emo-saint"></i>',
		'0:)'                  => '<i class="fontelico-emo-saint"></i>',
		'O:)'                  => '<i class="fontelico-emo-saint"></i>',
		':|'                   => '<i class="fontelico-emo-sleep"></i>',
		'8-)'                  => '<i class="fontelico-emo-sunglasses"></i>',
		'-o-o-'                => '<i class="fontelico-emo-sunglasses"></i>',
		'\o-o/'                => '<i class="fontelico-emo-sunglasses"></i>',
		'()-()'                => '<i class="fontelico-emo-sunglasses"></i>',
		':o'                   => '<i class="fontelico-emo-surprised"></i>',
		':-O'                  => '<i class="fontelico-emo-surprised"></i>',
		':O'                   => '<i class="fontelico-emo-surprised"></i>',
		'*SURPRISED*'          => '<i class="fontelico-emo-surprised"></i>',
		':P'                   => '<i class="fontelico-emo-tongue"></i>',
		':p'                   => '<i class="fontelico-emo-tongue"></i>',
		':-P'                  => '<i class="fontelico-emo-tongue"></i>',
		':-p'                  => '<i class="fontelico-emo-tongue"></i>',
		'=('                   => '<i class="fontelico-emo-unhappy"></i>',
		':-('                  => '<i class="fontelico-emo-unhappy"></i>',
		':('                   => '<i class="fontelico-emo-unhappy"></i>',
		';-)'                  => '<i class="fontelico-emo-wink"></i>',
		';)'                   => '<i class="fontelico-emo-wink"></i>',
		'~_^'                  => '<i class="fontelico-emo-wink2"></i>',
		// STICKERS
		':lol:'                => '<img src="/images/stickers/lol.png" alt=":lol:" />',
		':challenge-accepted:' => '<img src="/images/stickers/challenge-accepted.png" alt=":challenge-accepted:" />',
		'challenge accepted'   => '<img src="/images/stickers/challenge-accepted.png" alt=":challenge-accepted:" />',
		':how-about-a-no:'     => '<img src="/images/stickers/how-about-a-no.png" alt=":how-about-a-no:" />',
		':NO:'                 => '<img src="/images/stickers/how-about-a-no.png" alt=":how-about-a-no:" />',
		'how about a no'       => '<img src="/images/stickers/how-about-a-no.png" alt=":how-about-a-no:" />',
		'okay'                 => '<img src="/images/stickers/okay.png" alt="okay" />',
		'WHY'                  => '<img src="/images/stickers/why.png" alt="WHY" />',
		':fuck-yeah:'          => '<img src="/images/stickers/yeah.png" alt=":fuck-yeah:" />',
		'fuck yeah'            => '<img src="/images/stickers/yeah.png" alt=":fuck-yeah:" />',
	];

	public function __construct()
	{
		$this->smileys = [
			// SMILEYS
			'&lt;3'                => '<i class="fa fa-heart"></i>',
			':heart'               => '<i class="fa fa-heart"></i>',
			':like'                => '<i class="fa fa-thumbs-up"></i>',
			':thumbsup:'           => '<i class="fontelico-emo-thumbsup"></i>',
			'(y)'                  => '<i class="fontelico-emo-thumbsup"></i>',
			':boo'                 => '<i class="fa fa-thumbs-down"></i>',
			':)'                   => '<i class="fontelico-emo-happy"></i>',
			':/'                   => '<i class="fontelico-emo-displeased"></i>',
			'>:)'                  => '<i class="fontelico-emo-devil"></i>',
			'}:-)'                 => '<i class="fontelico-emo-devil"></i>',
			':_('                  => '<i class="fontelico-emo-cry"></i>',
			':\'('                 => '<i class="fontelico-emo-cry"></i>',
			'T_T'                  => '<i class="fontelico-emo-cry"></i>',
			';_;'                  => '<i class="fontelico-emo-cry"></i>',
			':-D'                  => '<i class="fontelico-emo-grin"></i>',
			':D'                   => '<i class="fontelico-emo-grin"></i>',
			'XD'                   => '<i class="fontelico-emo-laugh"></i>',
			':))'                  => '<i class="fontelico-emo-laugh"></i>',
			'0:-)'                 => '<i class="fontelico-emo-saint"></i>',
			'O:-)'                 => '<i class="fontelico-emo-saint"></i>',
			'0=)'                  => '<i class="fontelico-emo-saint"></i>',
			'O=)'                  => '<i class="fontelico-emo-saint"></i>',
			'0:)'                  => '<i class="fontelico-emo-saint"></i>',
			'O:)'                  => '<i class="fontelico-emo-saint"></i>',
			':|'                   => '<i class="fontelico-emo-sleep"></i>',
			'8-)'                  => '<i class="fontelico-emo-sunglasses"></i>',
			'-o-o-'                => '<i class="fontelico-emo-sunglasses"></i>',
			'\o-o/'                => '<i class="fontelico-emo-sunglasses"></i>',
			'()-()'                => '<i class="fontelico-emo-sunglasses"></i>',
			':o'                   => '<i class="fontelico-emo-surprised"></i>',
			':-O'                  => '<i class="fontelico-emo-surprised"></i>',
			':O'                   => '<i class="fontelico-emo-surprised"></i>',
			'*SURPRISED*'          => '<i class="fontelico-emo-surprised"></i>',
			':P'                   => '<i class="fontelico-emo-tongue"></i>',
			':p'                   => '<i class="fontelico-emo-tongue"></i>',
			':-P'                  => '<i class="fontelico-emo-tongue"></i>',
			':-p'                  => '<i class="fontelico-emo-tongue"></i>',
			'=('                   => '<i class="fontelico-emo-unhappy"></i>',
			':-('                  => '<i class="fontelico-emo-unhappy"></i>',
			':('                   => '<i class="fontelico-emo-unhappy"></i>',
			';-)'                  => '<i class="fontelico-emo-wink"></i>',
			';)'                   => '<i class="fontelico-emo-wink"></i>',
			'~_^'                  => '<i class="fontelico-emo-wink2"></i>',
			// STICKERS
			':lol:'                => '<img src="' . asset("/images/stickers/lol.png") . '" alt=":lol:" />',
			':challenge-accepted:' => '<img src="' . asset("/images/stickers/challenge-accepted.png") . '" alt=":challenge-accepted:" />',
			'challenge accepted'   => '<img src="' . asset("/images/stickers/challenge-accepted.png") . '" alt=":challenge-accepted:" />',
			':how-about-a-no:'     => '<img src="' . asset("/images/stickers/how-about-a-no.png") . '" alt=":how-about-a-no:" />',
			':NO:'                 => '<img src="' . asset("/images/stickers/how-about-a-no.png") . '" alt=":how-about-a-no:" />',
			'how about a no'       => '<img src="' . asset("/images/stickers/how-about-a-no.png") . '" alt=":how-about-a-no:" />',
			'okay'                 => '<img src="' . asset("/images/stickers/okay.png") . '" alt="okay" />',
			'WHY'                  => '<img src="' . asset("/images/stickers/why.png") . '" alt="WHY" />',
			':fuck-yeah:'          => '<img src="' . asset("/images/stickers/yeah.png") . '" alt=":fuck-yeah:" />',
			'fuck yeah'            => '<img src="' . asset("/images/stickers/yeah.png") . '" alt=":fuck-yeah:" />',
		];
	}

	

	/**
	 * @return array
	 */
	public function getAllSmileys()
	{
		return $this->smileys;
	}

	/**
	 * @return array
	 */
	public function setAllSmileys( array $smileys )
	{
		return $this->smileys = $smileys;
	}

	/**
	 * @param  $string
	 *
	 * @return string
	 */
	private function smiley( $string )
	{
		return str_replace( array_keys( $this->getAllSmileys() ), array_values( $this->getAllSmileys() ), $string );
	}

	/**
	 * @param        $data
	 * @param int    $code
	 * @param string $callback
	 *
	 * @return mixed
	 */
	public function responseInJSON( $data, $code = 200, $callback = 'callback' )
	{
		if( Request::ajax() || Request::wantsJson() )
			return response()->jsonp( Request::input( $callback ), $data, $code );

		return response( $data, $code );
	}

	/**
	 * @param string $message
	 * @param array  $data
	 * @param int    $status
	 * @param string $callback
	 *
	 * @return mixed
	 */
	public function responseSuccess( $message = '', $data = [], $status = 200, $callback = 'callback' )
	{
		return $this->responseInJSON( [ 'success' => [
			'title'   => 'Nice!',
			'message' => $message,
			'data'    => $data
		] ], $status, $callback );
	}

	/**
	 * @param string $message
	 * @param array  $data
	 * @param int    $status
	 * @param string $callback
	 *
	 * @return mixed
	 */
	public function responseError( $message = '', $data = [], $status = 404, $callback = 'callback' )
	{
		return $this->responseInJSON( [ 'error' => [
			'title'   => 'Whoops!',
			'message' => $message,
			'data'    => $data
		] ], $status, $callback );
	}

	/**
	 * @param       $strings
	 * @param array $allowedTags
	 *
	 * @return string
	 */
	public function filterBody( $strings, $allowedTags = [ 'p', 'b', 'em', 'img', 'u', 'i' ] )
	{
		$sanitizer = new HTMLSanitizer;
		$allowed   = $this->filterBodyAllowedTags( $allowedTags );

		return $this->smiley( $sanitizer->sanitize( $this->singleBreak( strip_tags( $strings, $allowed ) ) ) );
	}

	/**
	 * Filter with no smileys included
	 * 
	 * @param       $strings
	 * @param array $allowedTags
	 *
	 * @return string
	 */
	public function filterBodyOnly( $strings, $allowedTags = [ 'p', 'b', 'em', 'img', 'u', 'i' ] )
	{
		$sanitizer = new HTMLSanitizer;
		$allowed   = $this->filterBodyAllowedTags( $allowedTags );

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
	private function filterBodyAllowedTags( array $tags = [ ] )
	{
		array_walk( $tags, function ( & $value, $key )
		{
			$value = '<' . $value . '>';
		} );

		return implode( '', $tags );
	}

}
