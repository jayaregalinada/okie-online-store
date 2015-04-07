<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('categories')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'Superbreak',
				'description' => '',
				'created_at' => '2015-03-26 00:22:33',
				'updated_at' => '2015-03-27 03:45:04',
				'navigation' => 1,
				'slug' => 'superbreak',
			),
			1 => 
			array (
				'id' => 3,
				'name' => 'Right Pack',
				'description' => '',
				'created_at' => '2015-03-26 00:22:40',
				'updated_at' => '2015-03-27 03:37:11',
				'navigation' => 1,
				'slug' => 'right-pack',
			),
			2 => 
			array (
				'id' => 4,
				'name' => 'Overexposed',
				'description' => '',
				'created_at' => '2015-03-26 19:15:17',
				'updated_at' => '2015-03-27 03:37:15',
				'navigation' => 1,
				'slug' => 'overexposed',
			),
			3 => 
			array (
				'id' => 5,
				'name' => 'Best Seller',
				'description' => '',
				'created_at' => '2015-03-26 19:16:35',
				'updated_at' => '2015-03-27 03:37:17',
				'navigation' => 1,
				'slug' => 'best-seller',
			),
		));
	}

}
