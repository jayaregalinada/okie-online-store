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
				'parent_id' => 4,
				'created_at' => '2015-04-17 06:47:48',
				'updated_at' => '2015-04-29 10:16:17',
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'Superbreak',
				'description' => '',
				'slug' => 'superbreak',
				'navigation' => 1,
				'parent_id' => 4,
				'created_at' => '2015-04-17 07:16:12',
				'updated_at' => '2015-04-29 10:16:11',
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'Overexposed',
				'description' => '',
				'slug' => 'overexposed',
				'navigation' => 1,
				'parent_id' => 4,
				'created_at' => '2015-04-17 07:45:03',
				'updated_at' => '2015-04-29 10:16:04',
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'Jansport Bags',
				'description' => '',
				'slug' => 'jansport-bags',
				'navigation' => 1,
				'parent_id' => 4,
				'created_at' => '2015-04-29 10:15:20',
				'updated_at' => '2015-04-29 10:15:20',
			),
			4 => 
			array (
				'id' => 5,
				'name' => 'Fashionice Bags',
				'description' => '',
				'slug' => 'fashionice-bags',
				'navigation' => 1,
				'parent_id' => 5,
				'created_at' => '2015-04-29 10:15:25',
				'updated_at' => '2015-04-29 10:15:25',
			),
			5 => 
			array (
				'id' => 6,
				'name' => 'Big Student',
				'description' => '',
				'slug' => 'big-student',
				'navigation' => 1,
				'parent_id' => 4,
				'created_at' => '2015-04-29 10:15:55',
				'updated_at' => '2015-04-29 10:16:23',
			),
			6 => 
			array (
				'id' => 7,
				'name' => 'Sling Bags',
				'description' => '',
				'slug' => 'sling-bags',
				'navigation' => 1,
				'parent_id' => 4,
				'created_at' => '2015-04-29 10:16:40',
				'updated_at' => '2015-04-29 10:16:47',
			),
		));
	}

}
