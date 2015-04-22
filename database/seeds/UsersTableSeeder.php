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
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xap1/v/t1.0-1/10906447_10203536411106176_614047064970395546_n.jpg?oh=42999f945699587a5b770fa735fe4971&oe=55DC5749&__gda__=1437239512_65878f79be201d9eb1224d5fd0a4f8ce',
				'provider' => 'facebook',
				'provider_id' => '10203914650881934',
				'provider_token' => 'eyJpdiI6Iis5RUdpb0Z4WEtzN1hNZ1RXVDRrcXc9PSIsInZhbHVlIjoiZ1NEaXdmTlhXbEhsREF4ZGM0OE5XbG81RmRicUV0ZmdJT2c3Mm4rd21XdkZMdzd3ZVVKOCtNSzVHazU1b0xpenN1a2lad2tUcnpLbzcwMmJLTENVa2VGN2t0YmxzRzRXM2xnWmZuaUNBMm51SWNmMlRRdXhqU0FTZ1hOZlhhUlhtd00zOW1kS0ZXRlpWNEtKT3hcL0dlTWJERzR3RzNcL2puZjFRQVlHMkdrZGdWVzcrajJPT2pRYk5ENVhEWlV3UWN0dHlHcTl1OG5OUHhkWXR3ZmNZYzBZVENBSUpNajNCdUtsbmxNSlwvUm02dWRQZHdRTytlZ0Z3UVJPaTNKdTBnQ1l5WG5BSGJNMjVreGNnRGZlRmNubWhTMVNJYW84UlA4VDhRamtmVTluWU9HcHQ1XC8rbWhQVmJaWTM5S0U0RGdGIiwibWFjIjoiNzg4NDVkOTk0ZDNlNDhkYWJmNDgxNzVjOWE0ZmVjYjYzMmYwZTIzMTAzZjE0OWI3MGNiNGUyNWMzNjQ2YjM1ZSJ9',
				'password' => '$2y$10$DnsPCHuWsK9MDh9aDQmS3eHXKHN/5tSKg/zm2JB9bxf/kIZRBw4Ye',
				'verified' => 1,
				'gender' => 'male',
				'link' => 'https://www.facebook.com/app_scoped_user_id/10203914650881934/',
				'remember_token' => 'xwTs1yzQSBigd4Q5Z5GAJKuZQlHYkNlInaqxhByjGFUQQhFbeTrRoKTLMQQy',
				'created_at' => '2015-04-16 17:31:05',
				'updated_at' => '2015-04-16 17:31:05',
				'permission' => 0,
			),
			1 => 
			array (
				'id' => 2,
				'first_name' => 'Donna',
				'last_name' => 'Qinsen',
				'email' => 'donna_uecwlwz_qinsen@tfbnw.net',
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfa1/v/t1.0-1/603119_1385519888434624_8226920931319243369_n.jpg?oh=efa4bd469965628b80f3c10abed9624d&oe=55AE3D3E&__gda__=1440396677_3303e48ddb5583f9f04eb5936cb70447',
				'provider' => 'facebook',
				'provider_id' => '1385519391768007',
				'provider_token' => 'eyJpdiI6Im5Mck55dmVNOXJ6VGRhdzdcL1lWNnhRPT0iLCJ2YWx1ZSI6Im40MmZMRlcxSGs2dkxkMTVodmppZ0IxdXV0TzdJZ1dFUTJcL2FwXC95eFc1bFBacURXTVZIM2Q2TVF6Z1p3alhvSlFzMDh4TlBUaHBvT21kdGlxRVZCMUdWakJpNDJEbGExVXpMbjdXVURUcnZsc1VLSTgxYTMwZE93YyszOE5iRW42RFUwQ0NpcGFxWVlGMGlVOFd0cmMrVHQ0QTBNUWpwM1k2MUNwVEU2eW9uaDByWkNqZUxKcExzeDhoN3dxc2ZNemx0c3RYMmFXQmNPSDJWejc4SkpWTHQ3dW5qVUpzRlVZMEdPcmdIZUFxVDcwZXRWNEI3YTN0cmdzWWo2SUVDeE5yRjZpN1lsR1lIVFBQYW5iZzNKN0lQT1wvRFVwOGhJQzF1cnluMjRTN3ZwWWhzTHhpc25wcnVxaVgzT2RHQzZBIiwibWFjIjoiMzEyYWIwMDcyMWRkOWYwMjE5YTk1ZmQ5NDkxMzI4ZDRhMTFjYjUyZWVjY2U1ZWRjZWU3MGQ2OTJjNzQyNjU5YiJ9',
				'password' => '$2y$10$LeS0yJuDBjXVf2pMmUNite9iSNQiKOygEM0dvEgmDUldtA537XWX2',
				'verified' => 1,
				'gender' => 'female',
				'link' => 'https://www.facebook.com/app_scoped_user_id/1385519391768007/',
				'remember_token' => 'S5EavsE78j2xwsdowM68u1zx7GOXwCASAeLovkSZWP7D400vATe1eqOLM2Nq',
				'created_at' => '2015-04-17 08:06:27',
				'updated_at' => '2015-04-17 08:08:53',
				'permission' => 1,
			),
			2 => 
			array (
				'id' => 3,
				'first_name' => 'Richard',
				'last_name' => 'Bharambesky',
				'email' => 'wpxmuhr_bharambesky_1426649837@tfbnw.net',
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xfa1/v/t1.0-1/p480x480/11069902_1413750218942674_795736687992518573_n.jpg?oh=4bcacad9750acbbfc32fec5d30c84c56&oe=55ADA434&__gda__=1440768701_76a8644d905b8cc54952e0a98e08a810',
				'provider' => 'facebook',
				'provider_id' => '1398319497152413',
				'provider_token' => 'eyJpdiI6IkR5WGF3eEF1RU1teFh6c1dnc2tETGc9PSIsInZhbHVlIjoiOFJJRllwdUNqNHdYNUhWNFJ3N1lydkVVZkRjT1FLZ2JHdDk2aHRcL3pkbG5xeVl5YW1kR29YN1gzUjdOQmFWUjdVQ0Q4RXArNXE1SDZnRlo2S0NvMm1aM0RnSERadFwvbXZ5RzNjcUFLTHdLS1BnK3NKQnVkQzBRQzZoa2RBRlhiNkpMQzdUY2dpWlVnUFdWelc0SERGZ29hbG1rUG5icVJOM0tSWmpmbENldHFFSGZadWRwWTVjQVkwWWRJYkgwMVIrckpPSUF1ampoanI5bEo4K1wvOHYrSmdwQldVSGZ2YmdsXC90bEtPcSt4U0pPd2lFMlVhalQ2RG1lT1E5MTVWV2lRT2VESDZ3dTdHTUFyT3lIblhnZCt0MDV5elwvbmdYdml6dU5xcldpNFV4YWdiTFgzdDBnVFZOd3M5TkJzek1BQyIsIm1hYyI6ImE1MjEwNGUwNDM5Y2E4ODk2ZmNlZTFmNGY4OWQ1YTc5YjI1NjJjY2JjY2FkNzQ1MDI2ZDcxZmViNTBlMDFmMjUifQ==',
				'password' => '$2y$10$wgAGtMRRhDIl6gQpF8T0tO.rUcSslUIFD4bZJgiJuVi4NZ2tf1iU2',
				'verified' => 1,
				'gender' => 'male',
				'link' => 'https://www.facebook.com/app_scoped_user_id/1398319497152413/',
				'remember_token' => 'vfQmmlpIvLmyNCeIXSiMwssCcxlFhnoBCsB6U4caild9JVsxDZjcueMAb5w5',
				'created_at' => '2015-04-17 08:09:01',
				'updated_at' => '2015-04-17 08:09:25',
				'permission' => 1,
			),
			3 => 
			array (
				'id' => 4,
				'first_name' => 'Joe',
				'last_name' => 'Laverdetstein',
				'email' => 'ckujbvg_laverdetstein_1426649843@tfbnw.net',
				'avatar' => 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/v/t1.0-1/10983183_1404890989827197_8850744879946980641_n.jpg?oh=01f346fe8ed4a78ae5d8f0760aadbca7&oe=559CE650&__gda__=1436780690_d476af72d9c3091f623345c12617212d',
				'provider' => 'facebook',
				'provider_id' => '1404891296493833',
				'provider_token' => 'eyJpdiI6IjU4RGk3bmxtSTBsMjBQMlZTZXBpVFE9PSIsInZhbHVlIjoiS3hsYUdOVVdLc29Qa1FyWEpZQiszVmQ1VVRFMEhxa01SbjJjVWp5UGlNejlrdmx3SFFhdU9nS0VEU0tJY0FFeTR6dk5SZEFRR3JpZVJcL1RESTRmTHExWWc3aUk3R2RtV1wvSHMrbzNJTEIrY3NZdU9wdDhhSjhIZDJxMmdGbkYzU2tyb3Qzb3d5Y0hJZjdMZDM5K0o3VU9oUmtXSlV2YzlIaFh4SUl0VSs1VkR2Q3l4SGg1YzBpdkd0eGJhbDFvdTdzY1lsNU10MmVxRXliSW1lK0xqdjhcL3pHeWNMeUdtbExTNWtDZllhYThkYVlFRm0yYlwvRW9JZVVBRmQ0THZGTXlCdGQrMHg1ZG5ROGlOcG9IVDBFRmhLZEM0d3lSUWlITUoyMGpGQjhQZzBFdjNYMndzTUhZK0pZaTNnRkhjR1BYIiwibWFjIjoiNDI1ZWI0ZTdjMjI1ZWZjYmZjM2I3ZWY1ZDQ4ZjQ2ZDZiMDAyYTM0OGJlZGQ2NTA3NzVlZjlkZGE3ZDdjMGI5NiJ9',
				'password' => '$2y$10$NSpAriXfBxKLM9kU5vRd8ueYjvjuFVztmfIb94BChvylnmE5cwLaC',
				'verified' => 1,
				'gender' => 'male',
				'link' => 'https://www.facebook.com/app_scoped_user_id/1404891296493833/',
				'remember_token' => 'VKvjXKkMpSu6HQ6GcqC4h9uef8LfoLOWG1721rQ7RpPuY4EdcTCyXJICsL8F',
				'created_at' => '2015-04-17 08:09:33',
				'updated_at' => '2015-04-17 08:09:33',
				'permission' => 1,
			),
		));
	}

}
