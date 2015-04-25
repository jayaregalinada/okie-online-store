<?php namespace Okie\Repositories;
/** TODO: PhpDocs */
interface ThreadInterface {

	public function getAll();

	public function get( $id );

	public function getConversations( $id );

}
