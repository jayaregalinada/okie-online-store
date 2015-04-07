<?php namespace Okie\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Request;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	public function responseInJSON( $data, $code = 200 )
	{
		return response()->json( $data, $code )->setCallback( Request::input( 'callback' ) );
	}

}
