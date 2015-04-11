<?php namespace Okie\Observers;

class ConversationObserver {

	/**
	 * When model is saved
	 * Update its related model
	 *
	 * @param $model
	 *
	 * @return mixed
	 */
	public function saved( $model )
	{
		return $model->taggable->touch();
	}

}
 