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

    public function forceLogin( $id )
    {
        if( app()->environment('local') )
            Auth::loginUsingId( $id );
            return redirect( route('me') );

        return abort( 400, 'This feature is only available on local mode' );
    }

    public function getArray()
    {
        $a = [
            'asdasd' => 'asdsd'
        ];

        $b = serialize( $a );
        
        return json_encode( unserialize( $b ) );
    }

    public function getProduct( $id = 1 )
    {
        return dd( Product::getFeatured() );
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
        // return Option::whereType( 'config' )->get();
        return dd( \Config::get( 'okie.banner.full_width' ) );
    }

    public function getCheck()
    {
        $number = "1";
        return dd( (bool) $number );
    }

    public function getReview( $id = 1 )
    {
        return dd( Review::find( $id ) );
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


    public function getBanner()
    {
        $value = '[{"width":851,"height":315,"url":"uploads\/1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_org.jpg","base_dir":"1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_org.jpg"},{"width":50,"height":50,"url":"uploads\/1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_sqr.jpg","base_dir":"1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_sqr.jpg"},{"width":500,"height":500,"url":"uploads\/1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_thn.jpg","base_dir":"1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_thn.jpg"},{"width":150,"height":56,"url":"uploads\/1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_sml.jpg","base_dir":"1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_sml.jpg"},{"width":300,"height":111,"url":"uploads\/1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_mdm.jpg","base_dir":"1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_mdm.jpg"},{"width":600,"height":222,"url":"uploads\/1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_lrg.jpg","base_dir":"1f075d7f8829e82bce44de7348861aca8230bd78\/2015-4-27-201837_61750cb4e456fd7d573c518d44045bf1253a013a_lrg.jpg"}]';

        //Option::create([
        //  'type' => 'banner',
        //  'key' => 'banner',
        //],[
        //  'value' => $value
        //]);
        $get = Option::whereType( 'banner' )->get();
        $i = [];
        foreach ($get as $key => $value)
        {
            $i[] = $value->value;
        }
        return $i;
    }

    public function getBadge()
    {
        $data = [
            'title'         => 'HOT ITEM',
            'description'   => '',
            'slug'          => str_slug( 'HOT ITEM' ),
            'class'         => 'ribbon-default',
            'class_array'   => explode( ' ', 'ribbon-default' ),
            'color'         => '#cc2f2f'
        ];

        $product = Product::find( 17 )->update( [ 'badge' => $data ] );

        return Product::find( 17 );

    }

}
