<?php namespace Okie\Observers;

class OptionObserver {

	/**
	 * @param $model
	 */
	// public function creating( $model )
	// {
	// 	if( $this->checkIfKeyExists( $model ) )
	// 	{
	// 		return $this->updateValue( $model, $model->attributesToArray() );
			
	// 	}
	// 	else
	// 	{
	// 		return true;
	// 	}
	// }

	/**
	 * @param $model
	 *
	 * @return mixed
	 */
	private function checkIfKeyExists( $model )
	{
		return $model->whereKey( $model->key )->exists();
	}

	/**
	 * @param       $model
	 * @param array $attributes
	 *
	 * @return mixed
	 */
	private function updateValue( $model, array $attributes )
	{
		$model->whereKey( $model->key )->first()->fill( $attributes )->save();

		$instance = $model->whereKey( $model->key )->first();
		$instance->__set( 'updated', true );

		return $instance;
	}

}
 
