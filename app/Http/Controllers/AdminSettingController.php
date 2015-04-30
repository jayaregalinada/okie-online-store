<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Illuminate\Http\Request;

class AdminSettingController extends Controller {

	public function __construct()
	{
		$this->middleware( 'auth' );
		$this->middleware( 'admin' );
	}

	public function update()
	{
		// require base_path( 'vendor/autoload.php' ); // require composer dependencies

		// Composer\Factory::getHomeDir() method 
		// needs COMPOSER_HOME environment variable set
		putenv( 'COMPOSER_HOME=' . base_path( 'vendor/bin/composer' ) );

		// call `composer install` command programmatically
		// $input = new ArrayInput( [ 'command' => 'update' ] );
		// $application = new Application();
		// $application->setAutoExit( false ); // prevent `$application->run` method from exitting the script
		// $application->run();
	}

}
