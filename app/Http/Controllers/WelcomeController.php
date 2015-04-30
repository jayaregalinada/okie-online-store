<?php namespace Okie\Http\Controllers;

use Illuminate\Http\Request;
use Okie\Exceptions\OptionException;
use Okie\Option;
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

	/**
	 * Get all banner ads
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\OptionException
	 */
	public function getAllBanads()
	{
		$banners = Option::whereType( 'banner' )->whereKey( 'banner' );
		if( ! $banners->count() )
			throw new OptionException( 'banner', 'Sorry no banner at the moment' );

		$i = [];
		foreach( $banners->get() as $key => $value )
		{
			$i[] = $value->value;
		}

		return $this->responseSuccess( 'Successfully get all banads', [
			'banners' => $i,
			'interval' => config( 'okie.banner.interval' )
		] );
	}

}
