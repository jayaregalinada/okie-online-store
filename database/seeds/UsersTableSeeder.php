<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users')->delete();
        
		\DB::table('users')->insert(array (
			0 => 
			array (
				'id' => 1,
				'first_name' => 'Jay Are',
				'last_name' => 'Galinada',
				'email' => 'jayaregalinada@ymail.com',
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/p100x100/10906447_10203536411106176_614047064970395546_n.jpg?oh=6855770f88bf83cdcded287a7f06bd15&oe=55728167&__gda__=1437837720_1aa96e3dbbe833193351274080441231',
				'provider' => 'facebook',
				'provider_id' => '10203914650881934',
				'password' => NULL,
				'verified' => 1,
				'gender' => 'male',
				'link' => 'https://www.facebook.com/app_scoped_user_id/10203914650881934/',
				'remember_token' => 'cNV2wJTVaU0TBOBglUIKJA92uZ8aZj6KTJ1kyTLMsu0G6v1OwZw8eKy1tFno',
				'created_at' => '2015-03-26 00:21:34',
				'updated_at' => '2015-03-26 00:21:34',
				'permission' => 0,
			),
			1 => 
			array (
				'id' => 2,
				'first_name' => 'Donna',
				'last_name' => 'Qinsen',
				'email' => 'donna_uecwlwz_qinsen@tfbnw.net',
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfa1/v/t1.0-1/p100x100/603119_1385519888434624_8226920931319243369_n.jpg?oh=d428c4103db45249d5f5f621afe50a2f&oe=55734E04&__gda__=1437270914_02b76d76be0b65fb94a8f4d4eed82e06',
				'provider' => 'facebook',
				'provider_id' => '1385519391768007',
				'password' => NULL,
				'verified' => 1,
				'gender' => 'female',
				'link' => 'https://www.facebook.com/app_scoped_user_id/1385519391768007/',
				'remember_token' => '0p8iUvBKgOcQ196uQKAVzNW90rFSmMpCfRjUcfBOHL4ImfiYuRDxZhL3IxOv',
				'created_at' => '2015-03-28 23:08:57',
				'updated_at' => '2015-03-28 23:29:13',
				'permission' => 1,
			),
			2 => 
			array (
				'id' => 3,
				'first_name' => 'Richard',
				'last_name' => 'Bharambesky',
				'email' => 'wpxmuhr_bharambesky_1426649837@tfbnw.net',
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfp1/v/t1.0-1/s100x100/10354686_10150004552801856_220367501106153455_n.jpg?oh=d6269c35cfd00e2047ab87ed5a3a60e5&oe=55B0A273&__gda__=1433402981_23165d800d97738503e8b354e3f5ee5c',
				'provider' => 'facebook',
				'provider_id' => '1398319497152413',
				'password' => NULL,
				'verified' => 1,
				'gender' => 'male',
				'link' => 'https://www.facebook.com/app_scoped_user_id/1398319497152413/',
				'remember_token' => 'rQmNDKUtuH0FS0luQ0ImmG0nlxYRVGCGO5FpK8Aq9xlit7YUyTd46AVh5WPV',
				'created_at' => '2015-03-28 23:29:23',
				'updated_at' => '2015-03-29 00:08:27',
				'permission' => 1,
			),
			3 => 
			array (
				'id' => 4,
				'first_name' => 'Joe',
				'last_name' => 'Laverdetstein',
				'email' => 'ckujbvg_laverdetstein_1426649843@tfbnw.net',
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/p100x100/10983183_1404890989827197_8850744879946980641_n.jpg?oh=f6ed3200a07f909bef8f17872da63c4f&oe=55B1487E&__gda__=1438556284_487daaabb2a3f9808befaf55fcd90a0e',
				'provider' => 'facebook',
				'provider_id' => '1404891296493833',
				'password' => NULL,
				'verified' => 1,
				'gender' => 'male',
				'link' => 'https://www.facebook.com/app_scoped_user_id/1404891296493833/',
				'remember_token' => 'rAdRkrE6FBx0nxiMwpCQXwrnVExvUC1Z7buUOe17ndCw2z4Dtdh41PaL40mc',
				'created_at' => '2015-03-29 00:08:41',
				'updated_at' => '2015-03-29 00:08:41',
				'permission' => 1,
			),
			4 => 
			array (
				'id' => 5,
				'first_name' => 'Mark Fernan',
				'last_name' => 'Donguines',
				'email' => 'mfdonguines01@gmail.com',
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/p100x100/11103033_10205990829109253_2691803645111382713_n.jpg?oh=695837cb26cace0795ed4e57dd9ba80f&oe=55BA6FBE&__gda__=1436963887_def12992595e47a5d3ead0e3c222e5eb',
				'provider' => 'facebook',
				'provider_id' => '10206008996363423',
				'password' => NULL,
				'verified' => 1,
				'gender' => 'male',
				'link' => 'https://www.facebook.com/app_scoped_user_id/10206008996363423/',
				'remember_token' => 'rnGbeQq5ywfKrNPzvA0cXxbZS77kV1Xuc94Co3oB0o5HTdwHUx51mOc3KkZ8',
				'created_at' => '2015-03-30 23:45:06',
				'updated_at' => '2015-03-30 23:45:07',
				'permission' => 0,
			),
			5 => 
			array (
				'id' => 6,
				'first_name' => 'Ivy',
				'last_name' => 'Madarang',
				'email' => 'johnmarkgalbz13@gmail.com',
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xaf1/v/t1.0-1/p100x100/10600516_1499120743675744_5737709984915486476_n.jpg?oh=ee3991aacb64538df0286e9dde743dac&oe=55A8FB82&__gda__=1438471497_5ec8b38a79f7d2d53323ded3e99f5794',
				'provider' => 'facebook',
				'provider_id' => '1584352308485920',
				'password' => NULL,
				'verified' => 1,
				'gender' => 'male',
				'link' => 'https://www.facebook.com/app_scoped_user_id/1584352308485920/',
				'remember_token' => 'FIKOlfooODuCnpyR0JQbDgW6wEp4xGFGHZ45Qz1QYwwnWlTJ8n4T1BVEg5dS',
				'created_at' => '2015-03-31 00:06:49',
				'updated_at' => '2015-03-31 00:06:49',
				'permission' => 2,
			),
		));
	}

}
