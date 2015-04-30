<?php namespace Okie\Http\Controllers;

use Auth;
use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Okie\Review;
use Okie\Exceptions\ReviewException;

class ReviewController extends Controller {

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
	 * @return mixed
	 */
	public function getAll()
	{
		$review = Review::latest();
		if( ! $review->exists() )
			throw new ReviewException( 'No reviews found at the moment' );

		return $this->responseInJSON( $review->paginate() );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\ReviewException
	 */
	public function approvedReview( $id )
	{
		$review = Review::find( $id );
		if( ! $review->exists() )
			throw new ReviewException( 'No review with id ' . $id );
		if( ! Auth::user()->isPermitted() )
			throw new ReviewException( 'Sorry you are not allowed to approved this review', 400 );
		else
			if( $review->approved_by == Auth::id() )
				return $this->responseInJSON( [ 'success' => [
					'message' => 'Already approved by you', 
					'data' => Review::find( $id ) ]
				] );
			
			$review->update( [
				'approved_by' => Auth::id()
			] );

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfully approved review', 
			'data' => Review::find( $id ) ]
		] );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 * @throws \Okie\Exceptions\ReviewException
	 */
	public function unapprovedReview( $id )
	{
		$review = Review::find( $id );
		if( ! $review->exists() )
			throw new ReviewException( 'No review with id ' . $id );
		if( ! Auth::user()->isPermitted() )
			throw new ReviewException( 'Sorry you are not allowed to approved this review', 400 );
		else
			$review->update( [
				'approved_by' => null
			] );

		return $this->responseInJSON( [ 'success' => [
			'message' => 'Successfully unapproved review', 
			'data' => Review::find( $id ) ]
		] );
	}

}
