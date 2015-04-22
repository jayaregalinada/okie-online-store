<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okie\Product;
use Okie\Image;
use Okie\Category;
use Okie\Services\Product\Factory as ProductFactory;
use Okie\Services\Product\ImageProcessor;
use Okie\Exceptions\ProductException;

class ProductController extends Controller {

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
	 * View for creating new Product in JS
	 * 
	 * @return \Illuminate\View\View
	 */
	public function getCreateView()
	{
		return view( 'product.a_create' );
	}

	/**
	 * View for creating new product
	 * 
	 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function create()
	{
		if( empty( Category::all()->toArray() ) )
			return redirect( route('product.index') . '#/category' )->with(['error' => [
				'message' => 'Please add category first' ]
			]);

		return view('product.create');
	}

	/**
	 * Save new product
	 * 
	 * @param  \Okie\Services\Product\Factory  $product
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return object|array
	 */
	public function store( ProductFactory $product, Request $request )
	{
		$item = $product->execute( $request->except( [ 'ajax', '_token' ] ) );
		if ( $request->input( 'ajax' ) || $request->ajax() || $request->wantsJson() )
			return $this->responseInJSON( $item );

		return redirect()->route( 'product.show', $item['id'] )->with([
			'success' => [
				'message' => 'Successfully added new product'
			]
		]);
	}

	/**
	 * View for Add new image for a specific produc
	 * 
	 * @param  integer  $id
	 * 
	 * @return \Illuminate\View\View
	 */
	public function addImage( $id )
	{
		return view( 'product.addimage' )->with( 'product', Product::with(['images', 'categories'])->orderBy('created_at', 'desc')->find( $id ) );
	}

	/**
	 * Show product information with images and category
	 * 
	 * @param  integer  $id
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return mixed
	 */
	public function show( $id, Request $request )
	{
		$find = Product::with( [ 'images', 'categories' ] )->find( $id );
		$find->__set( 'related', Product::getRelated( $id ) );
		if( ! $find )
			throw new ProductException( 'We do not have that kind of product', 404 );
		if( $request->input( 'ajax' ) || $request->ajax() || $request->wantsJson() )
			return $this->responseInJSON( $find );

		return view( 'product.addimage' )->with( 'product', $find )->with( 'category', Category::lists('id', 'name') );
	}

	/**
	 * Add an Image to existing Product
	 *
	 * @param  integer  $id
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Okie\Services\Product\Factory  $factory
	 * 
	 * @return array
	 */
	public function addImagePost( $id, Request $request, ProductFactory $factory )
	{
		$directory = sha1( $request->file( 'file' )->getClientOriginalName() . date( "Y-n-d-His" ) ) . '/';
		$factory->createDirectory( $directory );
		$images = $factory->compileImage( $directory, $request->file( 'file' ), new ImageProcessor );
		$productInstance = Product::find( $id );
		$imageInstance = new Image([
			'sizes' => json_encode( $images ),
			'caption' => $productInstance->name
		]);

		return $this->responseInJSON( $productInstance->images()->save( $imageInstance ) );
	}

	/**
	 * Show images with specific product_id with paginate 10
	 *
	 * @param  integer $id
	 *
	 * @return \Okie\Image
	 */
	public function showImagesPaginate( $id )
	{
		return $this->responseInJSON( Image::whereProductId( $id )->latest( 'updated_at')->paginate() );
	}

	/**
	 * Show product images
	 *
	 * @param  integer  $id
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function showImages( $id, Request $request )
	{
		$find = Image::whereProductId( $id );
		$take = ( $request->get('take') ) ?: 10;
		$skip = ( $request->get('skip') ) ?: 0;

		return $this->responseInJSON( [
			'total'    => $find->count(),
			'per_page' => (int) $take,
			'from'     => $skip + 1,
			'to'       => $skip + $take,
			'data'     => $find->take( $take )->skip( $skip )->get()
		] );
	}

	/**
	 * Update product
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  integer  $id
	 *
	 * @return mixed
	 */
	public function update( Request $request, $id )
	{
		if( Product::find( $id )->update( $request->all() ) )
			return $this->responseInJSON( [ 'success' => [
				'title' => 'Good work!',
				'message' => 'Successfully update product',
				'data' => Product::find( $id ) ]
			] );

		return $this->responseInJSON( ['error' => [
			'message' => 'Something went wrong on updating' ]
		], 500 );
	}

	/**
	 * Show all products
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return $this|mixed
	 * @throws \Okie\Exceptions\ProductException
	 */
	public function index( Request $request )
	{
		$products = Product::latest( 'updated_at' );
		$category = Category::latest( 'updated_at' );
		if( $request->input( 'json' ) || $request->ajax() || $request->wantsJson() )
		{
			if ( ! $category->exists() )
			{
				throw new ProductException( 'You must create category first', 404, 'Whoops!', ['categories' => $category->get() ] );
			}
			elseif( ! $products->exists() )
			{
				throw new ProductException( 'Currently no available products', 404, 'Opps!', ['categories' => $category->get() ] );
			}
			else
			{
				return $this->responseInJSON( Product::with( [ 'images', 'categories' ])->latest( 'created_at' )->paginate() );
			}
		}
		else 
		{
			return view( 'product.index' )->with( 'category', Category::lists('id', 'name') );
		}
	}

	/**
	 * Update the category
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Okie\Product
	 */
	public function updateCategory( Request $request )
	{
		$find = Product::with( 'categories' )->find( $request->input( 'id' ) );
		$find->categories()->sync( $request->input( 'categories' ) );

		return $this->responseInJSON( Product::with('categories')->find( $request->input( 'id' ) ) );
	}

	/**
	 * Delete product
	 *
	 * @param  integer  $id
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
	 */
	public function destroy( $id, Request $request )
	{
		if( Product::find( $id )->delete() )
		{
			$response = [
				'success' => [
					'message' => 'Successfully deleted product ' . $id
				]
			];
			if( $request->ajax() || $request->wantsJson() )
			{
				return $this->responseInJSON( $response );
			}
			return redirect( route('product.index') . '#/all')->with( $response );
		}

		return $this->responseInJSON( ['error' => [
			'message' => 'Something went wrong on deleting product '. $id ]
		], 500 );
	}

	/**
	 * Post method in images
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  integer  $id
	 *
	 * @return mixed
	 */
	public function postImages( Request $request, $id )
	{
		if( $request->isMethod( 'delete' ) )
			return $this->deleteImages( $request->input( 'id' ), $request );

		return $this->changeThumbnail( $id, $request );
	}

	/**
	 * Change the product thumbnail
	 *
	 * @param  integer  $id
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
	 */
	private function changeThumbnail( $id, Request $request )
	{
		if( Product::find( $id )->update( [ 'thumbnail_id' => $request->input( 'id' ) ] ) )
		{
			$response = [
				'success' => [
					'title' => 'Nice!',
					'message' => 'Successfully Update Thumbnail'
				]
			];
			if( $request->ajax() || $request->wantsJson() )
			{
				return $this->responseInJSON( $response );
			}

			return redirect( route('product.show', [ $request->input('product_id') ]) )->with( $response );
		}

		return $this->responseInJSON( ['error' => [
			'message' => 'Something went wrong on updating thumbnail' ]
		], 500 );
	}

	/**
	 * Delete images in product
	 *
	 * @param  integer  $id
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
	 */
	private function deleteImages( $id, Request $request )
	{
		$find = Image::find( $id );
		if( $find->delete() )
		{
			$response = [
				'success' => [
					'title' => 'Nice!',
					'message' => 'Successfully deleted image ' . $id 
				]
			];
			if( $request->ajax() || $request->wantsJson() )
			{
				return $this->responseInJSON( $response );
			}

			return redirect( route('product.show', [ $request->input('product_id') ]) )->with( $response );
		}

		return $this->responseInJSON( ['error' => [
			'message' => 'Something went wrong on deleting image '. $id ]
		], 500 );
	}

	/**
	 * Inquire an item
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  integer  $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
	 */
	public function inquireItem( Request $request, $id )
	{
		return $this->responseInJSON( [
			'id'      => $id,
			'request' => $request
		] );
	}

	/**
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 */
	public function updateBadge( $id, Request $request )
	{
		$product = Product::find( $id );
		$product->editBadge( [
			'title'       => $request->input( 'title' ),
			'description' => $request->input( 'description' ),
			'slug'        => $request->input( 'title' ),
			'class'       => $request->input( 'class' )
		] );

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfully update product badge',
			'data' => Product::find( $id ) ]
		] );
	}

	public function removeBadge( $id )
	{
		Product::find( $id )->destroyBadge();

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfully remove product badge',
			'data' => Product::find( $id ) ]
		] );
	}

}
