<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeInConversationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('conversations', function(Blueprint $table)
		{
			$table->string( 'type' )->default( 'inquiry' )->after( 'id' );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('conversations', function(Blueprint $table)
		{
			$table->dropColumn( 'type' );
		});
	}

}
