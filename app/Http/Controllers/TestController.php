<?php namespace Okie\Http\Controllers;

/**
 * ===================================== *
 * This controller is for testing only
 * ===================================== *
 */

use Illuminate\Http\Request;
use Request as RequestFactory;
use Auth;
use Okie\MessageStatus;
use Okie\Message;
use Okie\User;
use Okie\Thread;
use Okie\Exceptions\ThreadException;
use Okie\Inquiry;
use Okie\Deliver;
use Okie\Services\HTMLSanitizer;

class TestController extends Controller {

	
	public function __construct()
	{
		$this->middleware( 'auth' );
		$this->middleware( 'admin' );
	}

	public function getQuery( Request $request )
	{
		dd( empty( $request->input( 'key' ) ) );
	}

}
