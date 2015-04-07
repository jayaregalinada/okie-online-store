<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okie\Product;
use Okie\Image;
use Okie\Category;
use Okie\Services\Product\Factory as ProductFactory;
use Okie\Services\Product\ImageProcessor;

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
		if ( $request->input( 'ajax' ) )
			return $item;

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
		$find = Product::with( [ 'images', 'categories' ] )->orderBy('created_at', 'desc')->find( $id );
		if( $request->input( 'ajax' ) )
			return response()->json( $find )
							 ->setCallback( $request->input( 'callback' ) );
			
		return view( 'product.addimage' )
			->with( 'product', $find )
			->with( 'category', Category::lists('id', 'name') );
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

		return $productInstance->images()->save( $imageInstance );
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
		return Image::whereProductId( $id )->paginate(10);
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

		return [
			'total' => $find->count(),
			'per_page' => (int) $take,
			'from' => $skip + 1,
			'to' => $skip + $take,
			'data' => $find->take( $take )->skip( $skip )->get()
		];
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
		$find = Product::find( $id );
		if( $find->update( $request->all() ) )
			return $find;

		return response(['error' => [
			'message' => 'Something went wrong on updating'
			]
		], 500);
	}

	/**
	 * Show all products
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return mixed
	 */
	public function index( Request $request )
	{
		if( $request->input( 'json' ) )
			return Product::with( [ 'images', 'categories' ])->orderBy('created_at', 'desc')->paginate()->appends( [ 'json' => true ] );

		return view( 'product.index' )
			->with( 'category', Category::lists('id', 'name') );
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
		$find = Product::with('categories')->find( $request->input( 'id' ) );
		$find->categories()->sync( $request->input( 'categories' ) );

		return Product::with('categories')->find( $request->input( 'id' ) );
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
		$product = Product::find( $id );
		if( $product->delete() )
		{
			$response = [
				'success' => [
					'message' => 'Successfully deleted product ' . $id 
				]
			];
			if( $request->ajax() )
			{
				return response( $response );
			}
			return redirect( route('product.index') . '#/all')->with( $response );
		}

		return response(['error' => [
			'message' => 'Something went wrong on deleting product '. $id
			]
		], 500);
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
		$find = Product::find( $id );
		if( $find->update( [ 'thumbnail_id' => $request->input( 'id' ) ] ) )
		{
			$response = [
				'success' => [
					'message' => 'Successfully Update Thumbnail'
				]
			];
			if( $request->ajax() )
			{
				return response( $response );
			}

			return redirect( route('product.show', [ $request->input('product_id') ]) )->with( $response );
		}

		return response(['error' => [
			'message' => 'Something went wrong on updating thumbnail'
			]
		], 500);
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
					'message' => 'Successfully deleted image ' . $id 
				]
			];
			if( $request->ajax() )
			{
				return response( $response );
			}

			return redirect( route('product.show', [ $request->input('product_id') ]) )->with( $response );
		}

		return response(['error' => [
			'message' => 'Something went wrong on deleting image '. $id
			]
		], 500);
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
		$response = [
			'id' => $id,
			'request' => $request
		];
		return response()->json( $response )
						 ->setCallback( $request->input( 'callback' ) );
	}

}
