<?php namespace Okie\Services\Inquiry;

use Okie\Inquiry;
use Okie\Conversation;
use Okie\Services\Inquiry\ImageProcessor;
use Validator, File, Config;
use Illuminate\Auth\Guard;

class UploadFactory {

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
	 */
	public function __construct( Guard $auth )
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
	 * @return \Okie\Conversation
	 */
	public function create( $data )
	{
		$inquiry = Inquiry::find( $data[ 'inquiry' ] );
		if( is_null( $inquiry ) )
			throw new ThreadException( 'INQUIRY', 'Inquiry not found' );
		$this->checkIfAllowed( $inquiry );
		$conversation           = new Conversation;
		$conversation->user_id  = $this->auth->user()->id;
		$conversation->body     = '<img data-large="'. $data[ 'images' ][3]['url'] .'" class="receipt" src="'. $data[ 'images' ][3]['url'] .'" alt="'. $data[ 'images' ][0]['url'] .'" />';
		$conversation->type     = ( $this->auth->user()->isPermitted() ) ? $conversation->responses[ 'inquiry' ] : 'inquiry';
		$inquiry->conversations()->save( $conversation );

		return Conversation::find( $conversation->id );
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
		if( ! File::exists( config( 'inquiry.upload.fullpath' ) . $folderName ) )
			return File::makeDirectory( config( 'inquiry.upload.fullpath' ) . $folderName, 0777, true );

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
			'jpg'   => config( 'inquiry.images.quality.jpg' ),
			'image' => config( 'inquiry.images.quality.image' ),
		];
		$saveDir = config( 'inquiry.upload.fullpath' ) . $directory;

		$images = [
			// ORIGINAL SIZE
			$processor->encodeImage(
				$file,
				$saveDir . $this->createFileName( config( 'inquiry.images.sizes.original.suffix' ), $file )
			),
			// SQUARE SIZE
			$processor->createSquare( 
				config( 'inquiry.images.sizes.square.size' ),
				$file, 
				$saveDir . $this->createFileName( config( 'inquiry.images.sizes.square.suffix' ), $file )
			),
			// THUMBNAIL SIZE
			$processor->createSquare( 
				config( 'inquiry.images.sizes.thumbnail.size' ),
				$file, 
				$saveDir . $this->createFileName( config( 'inquiry.images.sizes.thumbnail.suffix' ), $file ) 
			),
			// SMALL SIZE
			$processor->resizeImage(
				[
				'width' => config( 'inquiry.images.sizes.small.width' ), 
				'height' => config( 'inquiry.images.sizes.small.height' )
				], 
				$file,
				$saveDir . $this->createFileName( config( 'inquiry.images.sizes.small.suffix' ), $file )
			),
			// MEDIUM SIZE
			$processor->resizeImage(
				[
				'width' => config( 'inquiry.images.sizes.medium.width' ), 
				'height' => config( 'inquiry.images.sizes.medium.height' )
				], 
				$file,
				$saveDir . $this->createFileName( config( 'inquiry.images.sizes.medium.suffix' ), $file )
			),
			// LARGE SIZE
			$processor->resizeImage(
				[
				'width' => config( 'inquiry.images.sizes.large.width' ), 
				'height' => config( 'inquiry.images.sizes.large.height' )
				], 
				$file,
				$saveDir . $this->createFileName( config( 'inquiry.images.sizes.large.suffix' ), $file )
			)
		];

		foreach ( $images as $key => $value )
		{
			$image[] = [
				'width'     => $images[ $key ]->width(),
				'height'    => $images[ $key ]->height(),
				'url'       => asset( config( 'inquiry.upload.basename' ) ) . "/$directory$value->basename",
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

	/**
	 * Check if user is allowed
	 *
	 * @param $inquiry
	 *
	 * @throws \Okie\Exceptions\ThreadException
	 */
	protected function checkIfAllowed( $inquiry )
	{
		if( $this->auth->user()->isUser() && $inquiry->inquisition_id != $this->auth->id() )
			throw new ThreadException( 'INQUIRY', 'You are not allowed here', 401 );
	}


}

