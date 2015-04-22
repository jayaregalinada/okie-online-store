<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\Inquiry;
use Okie\Category;
use Okie\Product;
use Okie\Review;
use Okie\Http\Requests;
use Illuminate\Http\Request;
use Okie\Http\Controllers\Controller;
use Okie\Exceptions\ProductException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
		$products = Product::with( [ 'images', 'categories' ] )->latest( 'created_at' );
		if( ! $products->exists() )
			throw new ProductException( 'No products at the moment', 404 );
			
		return $this->responseInJSON( $products->paginate() );
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
		$find = Product::with( [ 'categories', 'images' ] )->find( $id );
		$find->__set( 'related', Product::getRelated( $id, 4 ) );
		if( Auth::check() )
			// $inquiry = Inquiry::getUserInquiry( $id, Auth::id() );
			if( Inquiry::getUserInquiry( $id, Auth::id() )->exists() )
				$find->__set( 'inquiry', Inquiry::getUserInquiry( $id, Auth::id() )->first() );
			if( Review::whereUserId( Auth::id() )->exists() )
				$find->__set( 'review', Review::whereUserId( Auth::id() )->whereProductId( $id )->first() );
		if( ! $find )
			throw new ProductException( 'We do not have that kind of product', 404 );

		if( $request->ajax() || $request->wantsJSON() )
			return $this->responseInJSON( $find );

		return view('item', [ 'product' => $find ] );
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
		if( ! $category )
			throw new ProductException( 'We do not have any category yet', 404 );
		if( ! $category->products()->exists() )
			throw new ProductException( 'We do not have any products on this category', 404 );

		return $this->responseInJSON( [
			'category' => $category, 
			'products' => $category->products()->latest( 'created_at' )->paginate()->toArray()
		] );
	}

	public function rateItem( Request $request, $id )
	{
		$review = Review::updateOrCreate([
			'product_id' => $id,
			'user_id' => Auth::id()
		], [
			'message' => ( is_null( $request->input( 'message' ) ) ? '' : $request->input( 'message' ) ),
			'rating'  => $request->input( 'rating' )
		] );
		if( Auth::user()->isPermitted() )
			$review->update( [
				'approved_by' => Auth::id()
			] );

		return $this->responseInJSON( [ 'success' => [
			'title' => 'Nice!',
			'message' => 'Thank you for reviewing this product',
			'data' => Review::find( $review->id ) ]
		] );
	}

}
