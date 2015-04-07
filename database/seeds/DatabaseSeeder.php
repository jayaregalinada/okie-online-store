<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('UsersTableSeeder');
		$this->call('CategoriesTableSeeder');
		$this->call('ProductsTableSeeder');
		$this->call('ProductCategoryTableSeeder');
		$this->call('ImagesTableSeeder');
		// $this->call('MessagesTableSeeder');
		// $this->call('MessageThreadTableSeeder');
		// $this->call('ThreadsTableSeeder');
	}

}
