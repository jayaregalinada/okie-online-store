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
	 * Get categories
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Okie\Category|\Illuminate\View\View
	 */
	public function getCategories( Request $request )
	{
		switch ( key( $request->input() ) )
		{
			case 'json':
			case 'all':
				return Category::all();
			break;

			case 'find':
				return Category::find( $request->input( 'find' ) );
			break;
			
			default:
				return view( 'settings.category' );
			break;
		}
	}

	/**
	 * Post a category
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Okie\Category
	 */
	public function postCategory( Request $request )
	{
		switch ( key( $request->input() ) )
		{
			case 'update':
				return $this->updateCategory( $request );
			break;

			case 'delete':
				return $this->deleteCategory( $request->input( 'id' ) );
			break;
			
			default:
				return $this->createCategory( $request->input( 'category' ) );
			break;
		}
	}

	/**
	 * Add a category
	 *
	 * @param \Illuminate\Http\Request $request
	 * 
	 * @return \Okie\Category
	 */
	private function addCategory( Request $request )
	{
		return Category::create( [ 'name' => $request->input( 'category' )] );
	}

	/**
	 * Update a category
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Okie\Category
	 */
	protected function updateCategory( $request )
	{
		$model = Category::find( $request->input( 'id' ) );
		$model->description = $request->input( 'description' );
		$model->name = $request->input( 'name' );
		$model->save();
		
		return $model;
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
	 * Delete a category
	 *
	 * @param  integer $id
	 *
	 * @return \Okie\Category
	 */
	protected function deleteCategory( $id )
	{
		return Category::destroy( $id );
	}

	/**
	 * Create a Category
	 *
	 * @param  string $name
	 *
	 * @return \Okie\Category
	 */
	protected function createCategory( $name )
	{
		return Category::create( [ 'name' => $name ] );
	}

}
