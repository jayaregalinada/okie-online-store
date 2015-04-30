<?php namespace Okie\Http\Controllers;

use Okie\Exceptions\OptionException;
use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Okie\Option;
use Illuminate\Http\Request;
use Okie\Services\Option\ImageProcessor;
use Okie\Services\Option\BannerFactory;

class OptionController extends Controller {

	/**
	 * Creating instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
		$this->middleware( 'admin' );
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\OptionException
	 */
	public function updateAppConfig( Request $request )
	{
		$option = Option::updateOrCreate([
			'type' => 'config',
			'key' => $request->input( 'key' ),
		], [
			'value' => $request->input( 'value' )
		] );
		if( ! $option )
			throw new OptionException( $request->input( 'key' ), "Something went wrong on updating configuration", 400 );
		
		// Updating model always returns boolean
		// Use @__toString() then json_decode to return an array
		return $this->responseSuccess( 'Successfully updated configuration', json_decode( $option->__toString() ) );
	}

	/**
	 * Add Banner for your applications
	 *
	 * @param \Illuminate\Http\Request            $request
	 * @param \Okie\Services\Option\BannerFactory $factory
	 *
	 * @return mixed
	 */
	public function addBanner( Request $request, BannerFactory $factory )
	{
		$directory = sha1( $request->file( 'file' )->getClientOriginalName() . date( "Y-n-d-His" ) ) . '/';
		$factory->createDirectory( $directory );
		$images = $factory->compileImage( $directory, $request->file( 'file' ), new ImageProcessor );
		$create = $factory->create( $images );

		return $this->responseInJSON( Option::find( $create->id ) );
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

		return $this->responseSuccess( 'Get all banner', [ 
			'banners' => $banners->get(),
			'interval' => config( 'okie.banner.interval' )
		] );
	}

	/**
	 * Remove the banner by ID
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\OptionException
	 */
	public function removeBanad( $id )
	{
		$banner = Option::whereType( 'banner' )->whereKey( 'banner' )->whereId( $id );
		$banners = Option::whereType( 'banner' )->whereKey( 'banner' );
		if( is_null( $banner ) )
			throw new OptionException( $id, 'Cannot remove banner because it does not exists', 400 );

		if( ! $banner->delete() )
			return $this->responseError( 'Somethings wrong on deleting this banad', [], 400 );

		return $this->responseSuccess( 'Successfully remove banner', [
			'banners' => $banners->get(),
			'interval' => config( 'okie.banner.interval' )
		] );
	}

}
