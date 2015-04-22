<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Okie\Option;
use Illuminate\Http\Request;

class OptionController extends Controller {

	public function updateApp_Config( Request $request )
	{
		return $request->all();
	}

	public function updateAppConfig( Request $request )
	{
		$option = Option::updateOrCreate([
			'type' => 'config',
			'key' => $request->input( 'key' ),
		], [
			'value' => $request->input( 'value' )
		] );

		return $this->responseInJSON( [ 'success' => [
			'title' => 'Nice!',
			'message' => 'Successfully updated configuration',
			'data' => $option ]
		] );
	}

}
