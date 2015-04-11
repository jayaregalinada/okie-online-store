<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadInbox extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('thread_inbox', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->unsignedInteger('sender_id');
			$table->unsignedInteger('recipient_id')->nullable();
			$table->timestamps();
			//if( ! app()->environment('local') )
			$table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('thread_inbox');
	}

}
