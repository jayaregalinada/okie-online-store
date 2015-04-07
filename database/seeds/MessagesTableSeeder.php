<?php

use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('messages')->insert(array (
			0 => 
			array (
				'id' => 1,
				'type' => 'inquire',
				'user_id' => 4,
				'product_id' => 6,
				'body' => '<p>inquire # 1</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-30 22:36:13',
				'updated_at' => '2015-03-30 22:36:13',
			),
			1 => 
			array (
				'id' => 2,
				'type' => 'inquire',
				'user_id' => 4,
				'product_id' => 6,
				'body' => '<p>Inquire # 2</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-30 22:36:19',
				'updated_at' => '2015-03-30 22:36:19',
			),
			2 => 
			array (
				'id' => 3,
				'type' => 'inquire',
				'user_id' => 4,
				'product_id' => 6,
				'body' => '<p>asda</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-30 22:36:23',
				'updated_at' => '2015-03-30 22:36:23',
			),
			3 => 
			array (
				'id' => 4,
				'type' => 'inquire',
				'user_id' => 5,
				'product_id' => 7,
				'body' => '<p>I like this one, how much?</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-30 23:45:43',
				'updated_at' => '2015-03-30 23:45:43',
			),
			4 => 
			array (
				'id' => 5,
				'type' => 'inquire',
				'user_id' => 5,
				'product_id' => 11,
				'body' => '<p>Dito magkano?</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-30 23:46:15',
				'updated_at' => '2015-03-30 23:46:15',
			),
			5 => 
			array (
				'id' => 6,
				'type' => 'inquire',
				'user_id' => 5,
				'product_id' => 11,
				'body' => '<p>Tska gusto ko ung orange</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-30 23:46:23',
				'updated_at' => '2015-03-30 23:46:23',
			),
			6 => 
			array (
				'id' => 7,
				'type' => 'inquire',
				'user_id' => 5,
				'product_id' => 11,
				'body' => '<p>Pati blue din po pala</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-30 23:46:30',
				'updated_at' => '2015-03-30 23:46:30',
			),
			7 => 
			array (
				'id' => 8,
				'type' => 'inquire',
				'user_id' => 6,
				'product_id' => 10,
				'body' => '<p>isa po akin<br/></p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-31 00:08:08',
				'updated_at' => '2015-03-31 00:08:08',
			),
			8 => 
			array (
				'id' => 9,
				'type' => 'inquire',
				'user_id' => 6,
				'product_id' => 10,
				'body' => '<p>mayron pink?</p><p><br/></p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-31 00:08:50',
				'updated_at' => '2015-03-31 00:08:50',
			),
			9 => 
			array (
				'id' => 10,
				'type' => 'inquire',
				'user_id' => 6,
				'product_id' => 1,
				'body' => '<p>isa din po nito<br/></p>',
				'deleted_at' => NULL,
				'created_at' => '2015-03-31 00:09:50',
				'updated_at' => '2015-03-31 00:09:50',
			),
			10 => 
			array (
				'id' => 11,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 1</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 19:01:42',
				'updated_at' => '2015-04-01 19:01:42',
			),
			11 => 
			array (
				'id' => 12,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 2</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 19:01:53',
				'updated_at' => '2015-04-01 19:01:53',
			),
			12 => 
			array (
				'id' => 13,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 3</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:10:19',
				'updated_at' => '2015-04-01 20:10:19',
			),
			13 => 
			array (
				'id' => 14,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 4</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:10:23',
				'updated_at' => '2015-04-01 20:10:23',
			),
			14 => 
			array (
				'id' => 15,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 5</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:10:26',
				'updated_at' => '2015-04-01 20:10:26',
			),
			15 => 
			array (
				'id' => 16,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 6</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:10:33',
				'updated_at' => '2015-04-01 20:10:33',
			),
			16 => 
			array (
				'id' => 17,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 7</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:10:36',
				'updated_at' => '2015-04-01 20:10:36',
			),
			17 => 
			array (
				'id' => 18,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 8</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:10:39',
				'updated_at' => '2015-04-01 20:10:39',
			),
			18 => 
			array (
				'id' => 19,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 8</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:10:48',
				'updated_at' => '2015-04-01 20:10:48',
			),
			19 => 
			array (
				'id' => 20,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 9</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:10:56',
				'updated_at' => '2015-04-01 20:10:56',
			),
			20 => 
			array (
				'id' => 21,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 10</p><p><br/></p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:11:05',
				'updated_at' => '2015-04-01 20:11:05',
			),
			21 => 
			array (
				'id' => 22,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 11</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:11:09',
				'updated_at' => '2015-04-01 20:11:09',
			),
			22 => 
			array (
				'id' => 23,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>i # 12</p><p>lJemel Tiuhttps://www.facebook.com/FSent.Official/photos/a.1014623795232409.1073741849.225807817447348/1014624125232376/?type=1&amp;theaterPa like nmn po... SalamatJay Are Galinada3/12, 2:37amJay Are GalinadaTapos na broJemel Tiu3/12, 2:38amJemel TiuSalamat bro ha..Jay Are Galinada3/12, 2:38amJay Are GalinadaNo worries </p><p><br/></p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:11:24',
				'updated_at' => '2015-04-01 20:11:24',
			),
			23 => 
			array (
				'id' => 24,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 12,
				'body' => '<p>Jemel Tiuhttps://www.facebook.com/FSent.Official/photos/a.1014623795232409.1073741849.225807817447348/1014624125232376/?type=1&amp;theaterPa like nmn po... SalamatJay Are Galinada3/12, 2:37amJay Are GalinadaTapos na broJemel Tiu3/12, 2:38amJemel TiuSalamat bro ha..Jay Are Galinada3/12, 2:38amJay Are GalinadaNo worries <br/></p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 20:11:28',
				'updated_at' => '2015-04-01 20:11:28',
			),
			24 => 
			array (
				'id' => 25,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 9,
				'body' => '<p>Meron ba kayong ibang perfume?</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 21:12:34',
				'updated_at' => '2015-04-01 21:12:34',
			),
			25 => 
			array (
				'id' => 26,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 10,
				'body' => '<p>I want this</p><p><br/></p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 21:12:44',
				'updated_at' => '2015-04-01 21:12:44',
			),
			26 => 
			array (
				'id' => 27,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 4,
				'body' => '<p>this is also</p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 21:13:14',
				'updated_at' => '2015-04-01 21:13:14',
			),
			27 => 
			array (
				'id' => 28,
				'type' => 'inquire',
				'user_id' => 1,
				'product_id' => 2,
				'body' => '<p>superbreak</p><p><br/></p>',
				'deleted_at' => NULL,
				'created_at' => '2015-04-01 21:13:23',
				'updated_at' => '2015-04-01 21:13:23',
			),
		));
	}

}
