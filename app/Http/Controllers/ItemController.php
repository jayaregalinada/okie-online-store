<?php namespace Okie\Http\Controllers;

use Okie\Category;
use Okie\Product;
use Okie\Http\Requests;
use Illuminate\Http\Request;
use Okie\Http\Controllers\Controller;

class ItemController extends Controller {

	/**
	 * Show all products
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return mixed|\Okie\Product
	 */
	public function index( Request $request )
	{
		return $this->responseInJSON( Product::with( [ 'images', 'categories' ] )->latest( 'created_at' )->paginate() );
	}

	/**
	 * Get Items index view
	 *
	 * @return \Illuminate\View\View
	 */
	public function getItemsIndexView()
	{
		return view( 'public.items' );
	}

	/**
	 * Get Item view
	 *
	 * @return \Illuminate\View\View
	 */
	public function getItemView()
	{
		return view( 'public.item' );
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param                          $id
	 *
	 * @return mixed
	 */
	public function show( Request $request, $id )
	{
		return $this->responseInJSON( Product::with([ 'categories', 'images' ])->find( $id ) );
	}

	/**
	 * Show products by category
	 * Category can be either id or slug
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param                          $id
	 *
	 * @return mixed|\Okie\Category
	 */
	public function showByCategory( Request $request, $id )
	{
		if( is_numeric( $id ) )
		{
			$category = Category::find( $id );
		}
		else
		{
			$category = Category::whereSlug( $id )->first();
		}

		return $this->responseInJSON( $category->products()->latest( 'created_at' )->paginate() );
	}

}
