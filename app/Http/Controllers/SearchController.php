<?php 
namespace Okie\Http\Controllers;

use Okie\User;
use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller {

	public function getUser( Request $request, $user = null )
	{
		$model = new User;
		if ( is_null( $user ) )
			return $this->errorResponse();

		switch ( $request->input( 'action' ) )
		{
			case 'id':
				return $model->whereId( $user )->exists() ?
					   $this->successResponse( $model->whereId( $user )->get() ) :
					   $this->errorResponse( 'No results found with that id' );
			break;

			case 'first_name':
				return $model->where( 'first_name', 'LIKE', "%$user%" )->exists() ?
					   $this->successResponse( $model->where( 'first_name', 'LIKE', "%$user%" )->get() ) :
					   $this->errorResponse( 'No results found with that first name' );
			break;

			case 'last_name':
				return $model->where( 'last_name', 'LIKE', "%$user%" )->exists() ?
					   $this->successResponse( $model->where( 'last_name', 'LIKE', "%$user%")->get() ) : 
					   $this->errorResponse( 'No results found with that last name' );
			break;

			case 'email':
				return $model->where( 'email', 'LIKE', "%$user%" )->exists() ?
					   $this->successResponse( $model->where( 'email', 'LIKE', "%$user%")->get() ) : 
					   $this->errorResponse( 'No results found with that last name' );
			break;

			default:
				$search = $model->where( 'first_name', 'LIKE', "%$user%" )
								->orWhere( 'last_name', 'LIKE', "%$user%" )
								->orWhere( 'email', 'LIKE', "%$user%" );

				return $search->exists() ?
					   $this->successResponse( $search->get() ) :
					   $this->errorResponse();
			break;
		}
	}

	private function errorResponse( $message = 'No results found' )
	{
		return $this->responseInJSON( [ 'error' => [ 'message' => $message ] ], 404 );
	}

	private function successResponse( $data )
	{
		return $this->responseInJSON( [ 'success' => [ 'data' => $data ] ] );
	}

}
