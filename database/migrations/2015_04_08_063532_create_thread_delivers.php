<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadDelivers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('thread_delivers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->unsignedInteger('product_id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('confirm_id');
			//if( ! app()->environment('local') )
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('confirm_id')->references('id')->on('users')->onDelete('cascade');
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
		Schema::drop('thread_delivers');
	}

}
