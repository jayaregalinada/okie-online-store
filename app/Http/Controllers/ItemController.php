<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Okie\Category;
use Okie\Product;
use Okie\Image;

class ItemController extends Controller {

	/**
	 * Show all products
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 */
	public function index( Request $request )
	{
		return response()->json( Product::with( [ 'images', 'categories' ] )->orderBy('created_at', 'desc')->paginate() )
						 ->setCallback( $request->input( 'callback' ) );
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
	 * Show Item by id
	 *
	 * @param  Request $request
	 * @param  integer  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( Request $request, $id )
	{
		return response()->json( Product::with([ 'categories', 'images' ])->find( $id ) )
						 ->setCallback( $request->input( 'callback' ) );
	}

	/**
	 * Show Items by category
	 *
	 * @param  Request $request
	 * @param  integer|string  $id
	 *
	 * @return mixed
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

		return response()->json( $category->products()->orderBy('created_at', 'desc')->paginate() )
						 ->setCallback( $request->input( 'callback' ) );
	}

}
