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

	public function getPrivacyPolicy( Request $request )
	{
		if( $request->ajax() || $request->wantsJson() )
			return $this->responseSuccess( 'Privacy Policy contents', config( 'okie.privacy_policy.contents' ) );

		return view( 'privacy_policy' )->with( [ 'contents' => config( 'okie.privacy_policy.contents' ), 'title' => 'Privacy Policy' ] );
	}

	public function getTermsAndConditions( Request $request )
	{
		if( $request->ajax() || $request->wantsJson() )
			return $this->responseSuccess( 'Terms and Conditions contents', config( 'okie.terms_and_conditions.contents' ) );

		return view( 'terms_and_conditions' )->with( [ 'contents' => config( 'okie.terms_and_conditions.contents' ), 'title' => 'Terms and Conditions' ] );
	}

}
