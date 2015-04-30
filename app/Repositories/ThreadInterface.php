<?php namespace Okie\Repositories;

interface ThreadInterface {

	/**
	 * @return mixed
	 */
	public function getAll();

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function get( $id );

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getConversations( $id );

}
