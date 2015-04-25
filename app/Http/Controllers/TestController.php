<?php namespace Okie\Http\Controllers;

/**
 * ===================================== *
 * This controller is for testing only
 * ===================================== *
 */

use Illuminate\Http\Request;
use Request as RequestFactory;
use Auth;
use Okie\MessageStatus;
use Okie\Message;
use Okie\User;
use Okie\Thread;
use Okie\Review;
use Okie\Exceptions\ThreadException;
use Okie\Inquiry;
use Okie\Deliver;
use Okie\Newsletter;
use Okie\Services\HTMLSanitizer;
use Okie\Product;
use Okie\Category;
use Config;
use Okie\Option;
use Illuminate\Config\Repository;
use File;
use Okie\Services\Response as OkieResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class TestController extends Controller {

	
	public function __construct()
	{
		$this->middleware( 'auth' );
		$this->middleware( 'admin' );
	}

	public function getArray()
	{
		$a = [
			'asdasd' => 'asdsd'
		];

		$b = serialize( $a );
		
		return json_encode( unserialize( $b ) );
	}

	public function getProduct( $id = 1)
	{
		return Product::getFeatured();
	}

	public function getCategory( $slug )
	{
		$category = Category::find( $slug );
		$childrenProducts = $category->getChildren()->latest( 'updated_at' )->with( 'products' )->get()->lists( 'products' );
		$i = $this->categorySkip( $childrenProducts );
		$paginate = new LengthAwarePaginator( $i, count( $i ), 2, null, [
			'path' => Paginator::resolveCurrentPath(),
		] );
		$total = $category->getChildren()->latest( 'updated_at' )->with( 'products' );
		// return $paginate;
		return ( $this->categorySkip( $category->getChildren()->latest( 'updated_at' )->with( 'products' )->get()->toArray() ) );
		// return Product::find( $slug );
		// return Category::whereSlug( $slug )->first();
	}

	public function categorySkip( $data = array(), $skip = 0 )
	{
		$i = [];
		foreach ( array_slice( $data, $skip  ) as $value )
		{
			foreach ( $value as $product )
			{
				$i[] = $product;
			}
		}

		return $i;
	}

	public function getCreateConfig()
	{
		$option = new Option;
		$option->type = 'config';
		$option->key = 'app.footer';
		$option->value = '&copy; __YEAR__ __TITLE__';
		$option->save();

		return $option;
	}

	public function changeValue( $string )
	{
		$value = [
			'__title__' => config( 'app.title' ),
			'__yearnow__' => date( "Y" )
		];

		return str_replace( array_keys( $value ), array_values( $value ), $string );
	}

	public function getConfig()
	{
		//$db = \DB::table( 'options' );
		//$config = $db->lists( 'value', 'key' );
		//foreach( $config as $key => $value )
		//{
		//	$config[ $key ] = unserialize( $value );
		//}
		//return $config;
		return dd( config( 'app.address' ) );
	}

	public function getCheck()
	{
		$number = "1";
		return dd( (bool) $number );
	}

	public function getReview( $id = 1 )
	{
		return Review::find( $id );
	}

	public function getReserve( $request )
	{
		$item = 10;
		$reserve = 5;
		if( $request < $reserve )
			return 'error';
		return ( $item - ( $request - $reserve ) );
	}

	public function getFile()
	{
		$contents = File::get( config_path( 'app.php' ) );

		echo '<textarea>'. $contents .'</textarea>';
	}

	public function getGulp()
	{
		$cmd = 'gulp';
		header('Content-Encoding: none;');
        set_time_limit(0);
        echo '<style>html{background:#000;color:lime;font-family:Courier,monospace}pre{white-space:normal;margin:0}</style>';
        $handle = popen( $cmd, "r");
        echo '<pre style="color:yellow;">====================[ START ]====================</pre><br />';
        echo '<pre style="color:red;">['. date( "H:i:s") .'] Running <b>'. $cmd .'</b> command</pre>';
		echo '<pre>';
        if (ob_get_level() == 0) 
            ob_start();
        while(!feof($handle)) {
            $buffer = fgets($handle);
            $buffer = trim(htmlspecialchars($buffer));
            echo $buffer . '<br />';
            echo str_pad('', 4096);
            ob_flush();
            flush();
            sleep(1);
        }
        echo '</pre>';
        pclose($handle);
        echo '<pre style="color:yellow;">====================[ DONE ]====================</pre>';
        ob_end_flush();
	}

}
