<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Okie\Category;
use Illuminate\Http\Request;

class SettingsController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * Index View
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view( 'settings.index' );
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getNewsletterView()
	{
		return view( 'settings.a_newsletter' );
	}

}
