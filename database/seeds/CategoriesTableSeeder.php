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
		\DB::table('categories')->delete();
        
		\DB::table('categories')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'Right Pack',
				'description' => '',
				'slug' => 'right-pack',
				'navigation' => 1,
				'parent_id' => 1,
				'created_at' => '2015-04-17 06:47:48',
				'updated_at' => '2015-04-17 06:47:48',
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'Superbreak',
				'description' => '',
				'slug' => 'superbreak',
				'navigation' => 1,
				'parent_id' => 2,
				'created_at' => '2015-04-17 07:16:12',
				'updated_at' => '2015-04-17 07:16:12',
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'Overexposed',
				'description' => '',
				'slug' => 'overexposed',
				'navigation' => 1,
				'parent_id' => 3,
				'created_at' => '2015-04-17 07:45:03',
				'updated_at' => '2015-04-17 07:45:03',
			),
		));
	}

}
