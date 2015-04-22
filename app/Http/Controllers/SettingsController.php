<?php namespace Okie\Http\Controllers;

use Okie\User;
use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Okie\Category;
use Illuminate\Http\Request;
use View;

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

	/**
	 * @return \Illuminate\View\View
	 */
	public function getNewsletterConfirmView()
	{
		return view( 'settings.a_newsletter-confirm-unsubscription' );
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getPermissionsView()
	{
		return view( 'settings.a_permissions' );
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 */
	public function changeUserPermission( Request $request )
	{
		$find = User::find( $request->input( 'user_id' ) );
		$find->permission = $request->input( 'permission' );
		if( ! $find->save() )
			return $this->responseInJSON( [ 'error' => [
				'title' => 'Opps!',
				'message' => 'Somethings went wrong while updating' ]
			] );

		return $this->responseInJSON( [ 'success' => [
			'title'   => 'Success!',
			'message' => $find->getFullName() .' is now ' . strtoupper( $find->permissions[ $request->input( 'permission' ) ] ),
			'data'    => User::find( $request->input( 'user_id' ) ) ]
		] );
	}

	public function getPublicView( $view )
	{
		if( View::exists( 'settings.a_' . $view ) )
			return view( 'settings.a_' . $view );
	}

}
