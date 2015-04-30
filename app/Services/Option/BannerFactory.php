<?php namespace Okie\Services\Option;

use Okie\Image;
use Okie\Option;
use Okie\Services\Option\ImageProcessor;
use Validator, File, Config;
use Illuminate\Auth\Guard;

class BannerFactory {

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
	 * @param  Image  $image
	 */
	public function __construct( Guard $auth, Image $image )
	{
		$this->auth = $auth;
		$this->date = date( "Y-n-d-His" );
		$this->time = time();
	}

	/**
	 * Create Banner
	 *
	 * @param  array  $data
	 *
	 * @return \Okie\Option
	 */
	public function create( $data )
	{
		return Option::create( [
			'type'  => 'banner',
			'key'   => 'banner',
			'value' => json_encode( $data ),
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
		if( ! File::exists( config( 'okie.upload.fullpath' ) . $folderName ) )
			return File::makeDirectory( config('okie.upload.fullpath') . $folderName, 0777, true );

		return true;
	}

	/**
	 * Compile images
	 *
	 * @param  string  $directory
	 * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
	 * @param  \Okie\Services\Option\ImageProcessor  $processor
	 *
	 * @return array
	 */
	public function compileImage( $directory, $file, ImageProcessor $processor )
	{
		$image = [];
		$quality = [
			'jpg'   => config( 'okie.banner.quality.jpg' ),
			'image' => config( 'okie.banner.quality.image' ),
		];
		$saveDir = config( 'okie.upload.fullpath' ) . $directory;

		$images = [
			// ORIGINAL SIZE
			$processor->encodeImage(
				$file,
				$saveDir . $this->createFileName( config( 'okie.banner.sizes.original.suffix' ), $file )
			),
			// SQUARE SIZE
			$processor->createSquare( 
				config( 'okie.banner.sizes.square.size' ),
				$file, 
				$saveDir . $this->createFileName( config( 'okie.banner.sizes.square.suffix' ), $file )
			),
			// THUMBNAIL SIZE
			$processor->createSquare( 
				config( 'okie.banner.sizes.thumbnail.size' ),
				$file, 
				$saveDir . $this->createFileName( config( 'okie.banner.sizes.thumbnail.suffix' ), $file ) 
			),
			// SMALL SIZE
			$processor->resizeImage(
				[
				'width' => config( 'okie.banner.sizes.small.width' ), 
				'height' => config( 'okie.banner.sizes.small.height' )
				], 
				$file,
				$saveDir . $this->createFileName( config( 'okie.banner.sizes.small.suffix' ), $file )
			),
			// MEDIUM SIZE
			$processor->resizeImage(
				[
				'width' => config( 'okie.banner.sizes.medium.width' ), 
				'height' => config( 'okie.banner.sizes.medium.height' )
				], 
				$file,
				$saveDir . $this->createFileName( config( 'okie.banner.sizes.medium.suffix' ), $file )
			),
			// LARGE SIZE
			$processor->resizeImage(
				[
				'width' => config( 'okie.banner.sizes.large.width' ), 
				'height' => config( 'okie.banner.sizes.large.height' )
				], 
				$file,
				$saveDir . $this->createFileName( config( 'okie.banner.sizes.large.suffix' ), $file )
			)
		];

		foreach ( $images as $key => $value )
		{
			$image[] = [
				'width'     => $images[ $key ]->width(),
				'height'    => $images[ $key ]->height(),
				'url'       => asset( config( 'okie.upload.basename' ) ) . "/$directory$value->basename",
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

