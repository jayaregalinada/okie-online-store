<?php

use Illuminate\Database\Seeder;

class ThreadsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('threads')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'Inquiring for Black Strap / Black Label by Joe Laverdetstein',
				'type' => 'inquire',
				'user_id' => 4,
				'product_id' => 6,
				'created_at' => '2015-03-30 22:36:13',
				'updated_at' => '2015-03-30 22:36:23',
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'Inquiring for Pink Strap / Pink Label by Mark Fernan Donguines',
				'type' => 'inquire',
				'user_id' => 5,
				'product_id' => 7,
				'created_at' => '2015-03-30 23:45:43',
				'updated_at' => '2015-03-30 23:45:43',
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'Inquiring for Sling Bag Plain by Mark Fernan Donguines',
				'type' => 'inquire',
				'user_id' => 5,
				'product_id' => 11,
				'created_at' => '2015-03-30 23:46:15',
				'updated_at' => '2015-03-30 23:46:30',
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'Inquiring for Lacoste Polo by Ivy Madarang',
				'type' => 'inquire',
				'user_id' => 6,
				'product_id' => 10,
				'created_at' => '2015-03-31 00:08:09',
				'updated_at' => '2015-03-31 00:08:50',
			),
			4 => 
			array (
				'id' => 5,
				'name' => 'Inquiring for Superbreak - Pinas by Ivy Madarang',
				'type' => 'inquire',
				'user_id' => 6,
				'product_id' => 1,
				'created_at' => '2015-03-31 00:09:50',
				'updated_at' => '2015-03-31 00:09:50',
			),
			5 => 
			array (
				'id' => 6,
				'name' => 'Inquiring for Big Student - Plain by Jay Are Galinada',
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'created_at' => '2015-04-01 19:01:43',
				'updated_at' => '2015-04-01 20:11:28',
			),
			6 => 
			array (
				'id' => 7,
				'name' => 'Inquiring for Can Can Perfume by Jay Are Galinada',
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 9,
				'created_at' => '2015-04-01 21:12:34',
				'updated_at' => '2015-04-01 21:12:34',
			),
			7 => 
			array (
				'id' => 8,
				'name' => 'Inquiring for Lacoste Polo by Jay Are Galinada',
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 10,
				'created_at' => '2015-04-01 21:12:44',
				'updated_at' => '2015-04-01 21:12:44',
			),
			8 => 
			array (
				'id' => 9,
				'name' => 'Inquiring for Right Pack - Navy by Jay Are Galinada',
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 4,
				'created_at' => '2015-04-01 21:13:14',
				'updated_at' => '2015-04-01 21:13:14',
			),
			9 => 
			array (
				'id' => 10,
				'name' => 'Inquiring for Superbreak - Galaxy by Jay Are Galinada',
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 2,
				'created_at' => '2015-04-01 21:13:23',
				'updated_at' => '2015-04-02 00:30:23',
			),
			10 => 
			array (
				'id' => 11,
				'name' => 'Inquiring for Superbreak - Pinas by Jay Are Galinada',
				'type' => 'reply',
				'user_id' => 1,
				'product_id' => 1,
				'created_at' => '2015-04-02 00:30:46',
				'updated_at' => '2015-04-02 00:30:46',
			),
			11 => 
			array (
				'id' => 12,
				'name' => 'Inquiring for Superbreak - Pinas by Jay Are Galinada',
				'type' => 'reply',
				'user_id' => 1,
				'product_id' => 1,
				'created_at' => '2015-04-02 00:30:58',
				'updated_at' => '2015-04-02 00:30:58',
			),
		));
	}

}
