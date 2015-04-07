<?php

use Illuminate\Database\Seeder;
use Okie\Product;

class ProductsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		// for ($i=0; $i < 10; $i++)
		// { 
		// 	Product::create([
		// 		'name' => 'Superbreak - Pinas',
		// 		'code' => 'SB - Pinas',
		// 		'description' => 'Jansport with a touch of Pilippine flag',
		// 		'price' => '2000.00',
		// 		'unit' => 2,
		// 		'user_id' => 1,
		// 	]);
		// 	Product::create([
		// 		'name' => 'Superbreak - Galaxy',
		// 		'code' => 'SB - Galaxy LE',
		// 		'description' => 'SB - Galaxy LE description',
		// 		'price' => '1000.00',
		// 		'unit' => 5,
		// 		'user_id' => 1,
		// 	]);
		// 	Product::create([
		// 		'name' => 'Superbreak - Galaxy',
		// 		'code' => 'SB - Galaxy LE',
		// 		'description' => 'SB - Galaxy LE description',
		// 		'price' => '1000.00',
		// 		'unit' => 5,
		// 		'user_id' => 1,
		// 		'thumbnail_id' => 17,
		// 	]);
		// 	Product::create([
		// 		'name' => 'Big Student - Plain',
		// 		'code' => 'jsbs-plain',
		// 		'description' => 'Jansport Big Student - Plain',
		// 		'price' => '1500.00',
		// 		'unit' => 5,
		// 		'user_id' => 1,
		// 		'thumbnail_id' => 0,
		// 	]);
		// 	Product::create([
		// 		'name' => 'Lacoste V-neck',
		// 		'code' => 'croco-v-neck',
		// 		'description' => 'Lacoste V-neck description',
		// 		'price' => '1400.00',
		// 		'unit' => 5,
		// 		'user_id' => 1,
		// 		'thumbnail_id' => 0,
		// 	]);
		// }
		\DB::table('products')->insert(array (
			0 => 
			array (
				'name' => 'Superbreak - Pinas',
				'code' => 'SB - Pinas',
				'description' => 'Jansport with a touch of Pilippine flag',
				'price' => '2000.00',
				'unit' => 2,
				'created_at' => '2015-03-26 00:23:02',
				'updated_at' => '2015-03-26 16:00:03',
				'user_id' => 1,
				'thumbnail_id' => 34,
				'deleted_at' => NULL,
			),
			1 => 
			array (
				'name' => 'Superbreak - Galaxy',
				'code' => 'SB - Galaxy LE',
				'description' => 'SB - Galaxy LE description',
				'price' => '1000.00',
				'unit' => 5,
				'created_at' => '2015-03-26 00:29:20',
				'updated_at' => '2015-03-26 03:17:44',
				'user_id' => 1,
				'thumbnail_id' => 17,
				'deleted_at' => NULL,
			),
			2 => 
			array (
				'name' => 'Overexposed - Pink',
				'code' => 'OE - Pink',
				'description' => 'OE - Pink description',
				'price' => '1000.00',
				'unit' => 5,
				'created_at' => '2015-03-26 03:26:54',
				'updated_at' => '2015-03-26 03:27:05',
				'user_id' => 1,
				'thumbnail_id' => 0,
				'deleted_at' => NULL,
			),
			3 => 
			array (
				'name' => 'Right Pack - Navy',
				'code' => 'RP - Navy',
				'description' => 'RP - Navy description',
				'price' => '1200.00',
				'unit' => 5,
				'created_at' => '2015-03-26 03:29:30',
				'updated_at' => '2015-03-26 15:59:11',
				'user_id' => 1,
				'thumbnail_id' => 26,
				'deleted_at' => NULL,
			),
			4 => 
			array (
				'name' => 'Pendleton + Benny Gold',
				'code' => 'JSPBG',
				'description' => 'Jansport + Pendleton + Benny Gold description',
				'price' => '2000.00',
				'unit' => 10,
				'created_at' => '2015-03-26 03:34:09',
				'updated_at' => '2015-03-26 19:32:08',
				'user_id' => 1,
				'thumbnail_id' => 29,
				'deleted_at' => NULL,
			),
			5 => 
			array (
				'name' => 'Black Strap / Black Label',
				'code' => 'JS Neon Pink 404',
				'description' => 'Black Strap / Black Label description',
				'price' => '1000.00',
				'unit' => 3,
				'created_at' => '2015-03-26 04:49:52',
				'updated_at' => '2015-03-26 04:49:58',
				'user_id' => 1,
				'thumbnail_id' => 0,
				'deleted_at' => NULL,
			),
			6 => 
			array (
				'name' => 'Pink Strap / Pink Label',
				'code' => 'JS Heart - 390',
				'description' => 'Pink Strap / Pink Label description',
				'price' => '1000.00',
				'unit' => 6,
				'created_at' => '2015-03-26 04:50:35',
				'updated_at' => '2015-03-26 04:50:41',
				'user_id' => 1,
				'thumbnail_id' => 0,
				'deleted_at' => NULL,
			),
			7 => 
			array (
				'name' => 'Overexposed - Galaxy',
				'code' => 'OE - Galaxy',
				'description' => 'Overexposed - Galaxy description',
				'price' => '1000.00',
				'unit' => 4,
				'created_at' => '2015-03-26 15:35:40',
				'updated_at' => '2015-03-27 04:10:44',
				'user_id' => 1,
				'thumbnail_id' => 0,
				'deleted_at' => NULL,
			),
			8 => 
			array (
				'name' => 'Can Can Perfume',
				'code' => 'can-can-perfume',
				'description' => 'Can Can Perfume Description',
				'price' => '5000.00',
				'unit' => 2,
				'created_at' => '2015-03-27 04:24:50',
				'updated_at' => '2015-03-27 04:29:09',
				'user_id' => 1,
				'thumbnail_id' => 37,
				'deleted_at' => NULL,
			),
			9 => 
			array (
				'name' => 'Lacoste Polo',
				'code' => 'croco-polo',
				'description' => 'Lacoste Polo description',
				'price' => '1000.00',
				'unit' => 4,
				'created_at' => '2015-03-27 04:32:52',
				'updated_at' => '2015-03-27 04:33:01',
				'user_id' => 1,
				'thumbnail_id' => 0,
				'deleted_at' => NULL,
			),
			10 => 
			array (
				'name' => 'Sling Bag Plain',
				'code' => 'sling-bag-plain',
				'description' => 'Sling Bag Plain description',
				'price' => '800.00',
				'unit' => 10,
				'created_at' => '2015-03-27 04:35:58',
				'updated_at' => '2015-03-27 04:36:05',
				'user_id' => 1,
				'thumbnail_id' => 0,
				'deleted_at' => NULL,
			),
			11 => 
			array (
				'name' => 'Big Student - Plain',
				'code' => 'jsbs-plain',
				'description' => 'Jansport Big Student - Plain',
				'price' => '1500.00',
				'unit' => 5,
				'created_at' => '2015-03-27 04:38:16',
				'updated_at' => '2015-03-27 04:38:22',
				'user_id' => 1,
				'thumbnail_id' => 0,
				'deleted_at' => NULL,
			),
			12 => 
			array (
				'name' => 'Lacoste V-neck',
				'code' => 'croco-v-neck',
				'description' => 'Lacoste V-neck description',
				'price' => '1400.00',
				'unit' => 5,
				'created_at' => '2015-03-27 04:40:14',
				'updated_at' => '2015-03-27 04:40:20',
				'user_id' => 1,
				'thumbnail_id' => 0,
				'deleted_at' => NULL,
			),
		));
	}

}
