<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadInquiries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('thread_inquiries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->unsignedInteger('inquisition_id');
			$table->unsignedInteger('product_id');
			$table->integer('reserve')->default( 0 );
			$table->timestamps();
			$table->foreign('inquisition_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('thread_inquiries');
	}

}
