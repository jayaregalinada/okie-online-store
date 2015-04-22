<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Okie\Category;
use Okie\Product;
use Okie\Exceptions\ProductException;
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
			case 'find':
				$find = Category::find( $request->input( 'find' ) );
				if( is_null( $find ) )
					throw new ProductException( 'Can not find category `' . $request->input( 'find' ) . '`', 404 );
					
				return $this->responseInJSON( Category::find( $request->input( 'find' ) ) );
			break;

			default:
				$categories = Category::latest( 'created_at' );
				if( ! $categories->exists() )
					throw new ProductException( 'No categories found at the moment', 404 );

				return $this->responseInJSON( $categories->paginate() );
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
				return $this->responseInJSON( [ 'success' => [
					'title' => 'Nice!',
					'message' => 'Category is successfully update',
					'data' => $this->updateCategory( $request ) ]
				] );
			break;

			case 'create':
				return $this->responseInJSON( [ 'success' => [
					'title' => 'Nice!',
					'message' => 'New Category', 
					'data' => $this->addCategory( $request->input( 'category' ) ) ]
				] );
			break;

			case 'delete':
				return $this->responseInJSON( [ 'success' => [
					'message' => 'Category is deleted',
					'title' => 'Success',
					'data' => $this->deleteCategory( $request->input( 'id' ) ) ]
				] );
			break;
			
			default:
				return $this->responseInJSON( [ 'error' => [
					'message' => 'NO! NO! NO!',
					'query' => $request->query() ]
				], 404 );
			break;
		}
	}

	/**
	 * Add Category
	 *
	 * @param  string $name
	 * 
	 * @return static|\Okie\Category
	 */
	private function addCategory( $name )
	{
		return $this->creatingCategory( [
			'name' => $name, 
			'slug' => str_slug( $name )
		] );
	}

	/**
	 * @param array $data
	 *
	 * @return static|\Okie\Category
	 */
	private function creatingCategory( array $data )
	{
		$category = Category::firstOrCreate( [
			'name' => $data[ 'name' ],
			'slug' => $data[ 'slug' ],
		] );
		$category->update( [
			'parent_id' => $category->id
		] );

		return $category;
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
		$model              = Category::find( $request->input( 'id' ) );
		$model->description = $request->input( 'description' );
		$model->name        = $request->input( 'name' );
		$model->slug        = $request->input( 'slug' );
		$model->navigation  = $request->input( 'navigation' );
		$model->parent_id   = $request->input( 'parent_selected' );
		$model->save();
		
		return $model;
	}

	/**
	 * Product index page
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view( 'product.index' );
	}

	/**
	 * Delete a Category
	 *
	 * @param  integer $id
	 *
	 * @return static|\Okie\Category
	 */
	private function deleteCategory( $id )
	{
		return Category::destroy( $id );
	}

}
