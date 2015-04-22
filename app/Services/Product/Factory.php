<?php namespace Okie\Services\Product;

use Okie\Product;
use Okie\Image;
use Okie\Services\Product\ImageProcessor;
use Validator, File, Config;
use Illuminate\Auth\Guard;

class Factory {

	/**
	 * @var \Illuminate\Auth\Guard
	 */
	protected $auth;

	/**
	 * @var string
	 */
	protected $date;

	/**
	 * @var integer
	 */
	protected $time;

	/**
	 * @param  Guard  $auth
	 * @param  Product  $product
	 * @param  Image  $image
	 */
	public function __construct( Guard $auth, Product $product, Image $image )
	{
		$this->auth = $auth;
		$this->date = date( "Y-n-d-His" );
		$this->time = time();
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator( array $data )
	{
		return Validator::make( $data, [
			'name'          => 'required|max:255',
			'code'          => 'required|max:255',
			'description'   => 'min:1',
			'price'         => 'required|numeric',
			'unit'          => 'required|numeric'
		] );
	}

	/**
	 * Validate before creation
	 *
	 * @param  array  $data
	 * @param  string $next
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function execute( $data, $next = null )
	{
		if( $this->validator( $data )->passes() )
		{
			if( $next )
				return redirect( $next );

			return $this->create( $data );
		}

		return response( $this->validator()->messages(), 500 );
	}

	/**
	 * Create Product
	 *
	 * @param  array  $data
	 *
	 * @return \Okie\Product
	 */
	protected function create( $data )
	{
		return Product::create( [
			'name'          => $data[ 'name' ],
			'code'          => $data[ 'code' ],
			'description'   => $data[ 'description' ],
			'price'         => $data[ 'price' ],
			'unit'          => $data[ 'unit' ],
			'user_id'       => $this->auth->user()->id
		] );
	}

	/**
	 * Create directory
	 * 
	 * @param  string  $folderName
	 *
	 * @return boolean
	 */
	public function createDirectory( $folderName )
	{
		if( ! File::exists( config( 'product.upload.fullpath' ) . $folderName ) )
			return File::makeDirectory( config('product.upload.fullpath') . $folderName, 0777, true );

		return true;
	}

	/**
	 * Compile images
	 *
	 * @param  string  $directory
	 * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
	 * @param  \Okie\Services\Product\ImageProcessor  $processor
	 *
	 * @return array
	 */
	public function compileImage( $directory, $file, ImageProcessor $processor )
	{
		$image = [];
		$quality = [
			'jpg'   => config( 'product.images.quality.jpg' ),
			'image' => config( 'product.images.quality.image' ),
		];
		$saveDir = config( 'product.upload.fullpath' ) . $directory;

		$images = [
			// ORIGINAL SIZE
			$processor->encodeImage(
				$file,
				$saveDir . $this->createFileName( config( 'product.images.sizes.original.suffix' ), $file )
			),
			// SQUARE SIZE
			$processor->createSquare( 
				config( 'product.images.sizes.square.size' ),
				$file, 
				$saveDir . $this->createFileName( config( 'product.images.sizes.square.suffix' ), $file )
			),
			// THUMBNAIL SIZE
			$processor->createSquare( 
				config( 'product.images.sizes.thumbnail.size' ),
				$file, 
				$saveDir . $this->createFileName( config( 'product.images.sizes.thumbnail.suffix' ), $file ) 
			),
			// SMALL SIZE
			$processor->resizeImage(
				[
				'width' => config( 'product.images.sizes.small.width' ), 
				'height' => config( 'product.images.sizes.small.height' )
				], 
				$file,
				$saveDir . $this->createFileName( config( 'product.images.sizes.small.suffix' ), $file )
			),
			// MEDIUM SIZE
			$processor->resizeImage(
				[
				'width' => config( 'product.images.sizes.medium.width' ), 
				'height' => config( 'product.images.sizes.medium.height' )
				], 
				$file,
				$saveDir . $this->createFileName( config( 'product.images.sizes.medium.suffix' ), $file )
			),
			// LARGE SIZE
			$processor->resizeImage(
				[
				'width' => config( 'product.images.sizes.large.width' ), 
				'height' => config( 'product.images.sizes.large.height' )
				], 
				$file,
				$saveDir . $this->createFileName( config( 'product.images.sizes.large.suffix' ), $file )
			)
		];

		foreach ( $images as $key => $value )
		{
			$image[] = [
				'width'     => $images[ $key ]->width(),
				'height'    => $images[ $key ]->height(),
				'url'       => config( 'product.upload.basename') . "$directory$value->basename",
				'base_dir'  => "$directory$value->basename"
			];
		}

		return $image;
	}

	/**
	 * Filename creation
	 *
	 * @param  string  $suffix
	 * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
	 * @param  string  $separator
	 * 
	 * @return string
	 */
	private function createFileName( $suffix, $file, $separator = '_' )
	{
		return $this->date . $separator . sha1( $file->getClientOriginalName() . $this->time ) . $separator . $suffix . '.jpg';
	}


}

