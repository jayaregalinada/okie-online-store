<?php namespace Okie\Services\Option;

use Image;

class ImageProcessor {

	/**
	 * Get Image quality from configuration
	 *
	 * @param  string  $item
	 *
	 * @return integer
	 */
	public function getQuality( $item )
	{
		return config( 'okie.banner.quality.' . $item );
	}

	/**
	 * Create a square size image
	 *
	 * @param  integer  $size
	 * @param  \Illuminate\Contracts\Filesystem\Filesystem  $file
	 * @param  string  $save
	 *
	 * @return \Intervention\Image\Image
	 */
	public function createSquare( $size, $file, $save )
	{
		return Image::make( $file )
			->fit( $size )
			->encode(
				'image/jpg',
				$this->getQuality( 'jpg' )
			)
			->save(
				$save,
				$this->getQuality( 'image' )
			);
	}

	/**
	 * Create a small size image
	 *
	 * @param  array  $size
	 * @param  \Illuminate\Contracts\Filesystem\Filesystem  $file
	 * @param  string  $save
	 *
	 * @return \Intervention\Image\Image
	 */
	public function resizeImage( $size, $file, $save )
	{
		$i = Image::make( $file );

		return $i
			->resize(
				$this->checkWidth( $i, $size[ 'width' ] ),
				$size[ 'height' ],
				function( $constraint )
				{
					$constraint->aspectRatio();
					$constraint->upsize();
				}
			)
			->encode(
				'image/jpg',
				$this->getQuality( 'jpg' )
			)
			->save(
				$save,
				$this->getQuality( 'image' )
			);
	}

	/**
	 * Encode original image
	 *
	 * @param  \Illuminate\Contracts\Filesystem\Filesystem  $file
	 * @param  string  $save
	 *
	 * @return \Intervention\Image\Image
	 */
	public function encodeImage( $file, $save )
	{
		return Image::make( $file )
			->encode(
				'image/jpg',
				$this->getQuality( 'jpg' )
			)
			->save(
				$save,
				$this->getQuality( 'image' )
			);
	}

	/**
	 * Check width first in resizing
	 *
	 * @param  \Intervention\Image\Image  $image
	 * @param  integer  $width
	 *
	 * @return integer
	 */
	private function checkWidth( $image, $width )
	{
		if( $image->width() < $width )
			return $image->width();

		return $width;
	}

	/**
	 * Check height first in resizing
	 *
	 * @param  \Intervention\Image\Image  $image
	 * @param  integer  $height
	 *
	 * @return integer
	 */
	private function checkHeight( $image, $height )
	{
		if( $image->height() < $height )
			return $image->height();

		return $height;
	}


}
