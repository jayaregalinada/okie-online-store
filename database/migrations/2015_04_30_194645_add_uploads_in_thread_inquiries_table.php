<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUploadsInThreadInquiriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('thread_inquiries', function(Blueprint $table)
		{
			$table->boolean( 'uploads' )->default( false )->after( 'reserve' );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('thread_inquiries', function(Blueprint $table)
		{
			$table->dropColumn( 'uploads' );
		});
	}

}
