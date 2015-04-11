<?php namespace Okie\Observers;

use Request;

class InquiryObserver {

	/**
	 * @param $model
	 *
	 * @return mixed
	 */
	public function creating( $model )
	{
		if( $this->checkIfThreadExists( $model ) )
			return $this->response( [ 'error' => [
				'message' => 'Sorry you already send an inquiry to this product. Please proceed to your inbox for possible response',
				'inquiry' => $this->getThreadExists( $model )
			]], 400 );
	}

	/**
	 * @param $model
	 *
	 * @return mixed
	 */
	private function checkIfThreadExists( $model )
	{
		return $model->whereProductId( $model->product_id )->whereInquisitionId( $model->inquisition_id )->exists();
	}

	/**
	 * @param $model
	 *
	 * @return mixed
	 */
	protected function getThreadExists( $model )
	{
		return $model->whereProductId( $model->product_id )->whereInquisitionId( $model->inquisition_id )->id;
	}

	/**
	 * @param        $data
	 * @param int    $code
	 * @param string $callback
	 *
	 * @return mixed
	 */
	private function response( $data, $code = 200, $callback = 'callback' )
	{
		return response()->json( $data, $code )->setCallback( Request::input( $callback ) );
	}
}
 