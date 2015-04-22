<?php namespace Okie\Http\Controllers;

use Illuminate\Http\Request;
use Okie\Product;

class WelcomeController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// $this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view( 'home' )->with( 'products', Product::latest()->take( 12 )->get() );
	}

	/**
	 * Get index view
	 *
	 * @return \Illuminate\View\View
	 */
	public function getIndexView()
	{
		return view( 'index' );
	}

}
