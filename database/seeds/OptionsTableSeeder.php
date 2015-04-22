<?php

use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('options')->delete();
        
	}

}
