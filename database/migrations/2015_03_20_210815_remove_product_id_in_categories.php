<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveProductIdInCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if( Schema::hasColumn('categories', 'product_id') )
		{
			Schema::table('categories', function(Blueprint $table)
			{
				$table->dropColumn('product_id');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('categories', function(Blueprint $table)
		{
			$table->integer('product_id')->unsigned();
		});
	}

}
