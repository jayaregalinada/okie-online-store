<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('message_status', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('status')->default(0);
			$table->string('type')->default('inquiry');
			$table->integer('message_id')->unsigned()->nullable();
			$table->integer('thread_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('message_status');
	}

}
