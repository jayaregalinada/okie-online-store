<?php 
namespace Okie\Http\Controllers;

use Auth;
use Okie\User;
use Okie\Product;
use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller {

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param null                     $user
	 *
	 * @return mixed
	 */
	public function getUser( Request $request, $user = null )
	{
		$model = new User;
		if ( is_null( $user ) )
			return $this->errorResponse();

		switch ( $request->input( 'action' ) )
		{
			case 'id':
				return $model->whereId( $user )->exists() ?
					   $this->successResponse( $model->whereId( $user )->get(), $user ) :
					   $this->errorResponse( 'No results found with that id' );
			break;

			case 'first_name':
				return $model->where( 'first_name', 'LIKE', "%$user%" )->exists() ?
					   $this->successResponse( $model->where( 'first_name', 'LIKE', "%$user%" )->get(), $user ) :
					   $this->errorResponse( 'No results found with that first name' );
			break;

			case 'last_name':
				return $model->where( 'last_name', 'LIKE', "%$user%" )->exists() ?
					   $this->successResponse( $model->where( 'last_name', 'LIKE', "%$user%")->get(), $user ) :
					   $this->errorResponse( 'No results found with that last name' );
			break;

			case 'email':
				return $model->where( 'email', 'LIKE', "%$user%" )->exists() ?
					   $this->successResponse( $model->where( 'email', 'LIKE', "%$user%")->get(), $user ) :
					   $this->errorResponse( 'No results found with that last name' );
			break;

			default:
				$search = $model->where( 'first_name', 'LIKE', "%$user%" )
								->orWhere( 'last_name', 'LIKE', "%$user%" )
								->orWhere( 'email', 'LIKE', "%$user%" );

				return $search->exists() ?
					   $this->successResponse( $search->limit( 10 )->get(), $user ) :
					   $this->errorResponse();
			break;
		}
	}

	/**
	 * @param string $message
	 *
	 * @return mixed
	 */
	private function errorResponse( $message = 'No results found' )
	{
		return $this->responseInJSON( [ 'error' => [ 'message' => $message ] ], 404 );
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	private function successResponse( $data, $query = null )
	{
		return $this->responseInJSON( [ 'success' => [ 'count' => count( $data ), 'data' => $data, 'searching' => $query ] ] );
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param null                     $product
	 *
	 * @return mixed
	 */
	public function getProduct( Request $request, $product = null )
	{
		$model = new Product;
		if( is_null( $product ) )
			return $this->errorResponse();

		switch ( $request->input( 'action' ) )
		{
			case 'price':
				return  $model->wherePrice( $product )->exists() ?
						$this->successResponse( $model->wherePrice( $product )->get(), $product ) :
						$this->errorResponse( 'No results found with that price' );
			break;

			case 'id':
				return  $model->whereId( $product )->exists() ?
						$this->successResponse( $model->whereId( $product )->get(), $product ) :
						$this->errorResponse( 'No results found with that ID' );
			break;

			default:
				$search = $model->where( 'name', 'LIKE', "%$product%" )->orWhere( 'code', 'LIKE', "%$product%" );

				return  $search->exists() ?
						$this->successResponse( $search->limit( 15 )->get(), $product ) :
						$this->errorResponse();
			break;
		}
	}

}
