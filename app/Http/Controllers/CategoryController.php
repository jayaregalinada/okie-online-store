<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Okie\Category;
use Okie\Product;

use Illuminate\Http\Request;

class CategoryController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
		$this->middleware( 'admin' );
	}

	/**
	 * Get Categories by GET request
	 *
	 * @param  Request $request
	 *
	 * @return mixed
	 */
	public function getCategories( Request $request )
	{
		switch ( key( $request->query() ) )
		{
			case 'json':
			case 'all':
				return $this->responseInJSON( $this->responseInJSON( Category::all() ) );
			break;

			case 'find':
				return $this->responseInJSON( Category::find( $request->input( 'find' ) ) );
			break;

			default:
				return abort( 404 );
			break;
		}
	}

	/**
	 * Get Categories by POST request
	 *
	 * @param  Request $request
	 *
	 * @return mixed
	 */
	public function postCategory( Request $request )
	{
		switch ( key( $request->query() ) )
		{
			case 'update':
				return $this->responseInJSON( $this->updateCategory( $request ) );
			break;

			case 'create':
				return $this->responseInJSON( $this->addCategory( $request->input( 'category' ) ) );
			break;

			case 'delete':
				return $this->responseInJSON( $this->deleteCategory( $request->input( 'id' ) ) );
			break;
			
			default:
				return $this->responseInJSON( $request->query(), 404 );
			break;
		}
	}

	/**
	 * Add Category
	 *
	 * @param  string $name
	 * 
	 * @return \Okie\Category
	 */
	private function addCategory( $name )
	{
		return Category::create( [ 'name' => $name ] );
	}

	/**
	 * Update a Category
	 *
	 * @param  Request $request
	 *
	 * @return \Okie\Category
	 */
	private function updateCategory( $request )
	{
		$model = Category::find( $request->input( 'id' ) );
		$model->description = $request->input( 'description' );
		$model->name = $request->input( 'name' );
		$model->slug = $request->input( 'slug' );
		$model->save();
		
		return $model;
	}

	/**
	 * Product index page
	 *
	 * @param  Request $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function index( Request $request )
	{
		return view( 'product.index' );
	}

	/**
	 * Delete a Category
	 *
	 * @param  integer $id
	 *
	 * @return \Okie\Category
	 */
	private function deleteCategory( $id )
	{
		return Category::destroy( $id );
	}

}
