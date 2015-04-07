<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::get('/', ['as' => 'index', 'uses' => 'WelcomeController@index']);

Route::get('home', function()
{
    return redirect('/');
});

Route::get('permission', 'UserController@getPermission');

Route::controller('test', 'TestController');

Route::get('/testing/force-login/{id}', function( $id )
{
    if( $this->app->environment('local') )
        Auth::loginUsingId( $id );
        return redirect( route('me') );

    return abort( 400, 'This feature is only available on local mode' );
});

// Route::controllers([
// 	'auth' => 'Auth\AuthController',
// 	'password' => 'Auth\PasswordController',
// ]);

Route::controller('auth', 'Auth\AuthController', [
    'getLogin' => 'auth.login',
    'postLogin' => 'auth.login.post'
]);

Route::get( 'login', function()
{
    return redirect('/auth/login');
});

Route::get('i', ['as' => 'i', 'uses' => 'UserController@getUserInfo']);

Route::get( 'login/{provider?}', ['as' => 'login.provider', 'uses' => 'Auth\AuthController@login'] );


Route::get('messages', ['as' => 'messages', 'uses' => 'HomeController@messages', 'middleware' => 'admin']);

// Route for Products
Route::resource('product', 'ProductController',
[
    'names' => [
        'create' => 'product.add'
    ]
]);
Route::match(['post', 'put', 'patch'], 'product/{product}/category', ['as' => 'product.update.category', 'uses' => 'ProductController@updateCategory']);
Route::post('product/{id}/add-image', ['as' => 'product.add_image.post', 'uses' => 'ProductController@addImagePost']);
Route::get('product/{product}/images', ['as' => 'product.images', 'uses' => 'ProductController@showImages']);
Route::match(['post', 'delete'], 'product/{product}/images', ['as' => 'product.images.post', 'uses' => 'ProductController@postImages']);

Route::group(['prefix' => 'views'], function()
{
    Route::get('product/create.html', 'ProductController@getCreateView');
    Route::get('index.html', 'WelcomeController@getIndexView');
    Route::get('product/lightbox.html', function()
    {
        return view( 'product.template_lightbox' );
    });
    Route::get('products/category.html', function(){ return view( 'product.a_category' ); });
    Route::get('products/index.html', function(){ return view( 'product.a_index'); });
    Route::get('settings/index.html', function(){ return view( 'settings.a_index' ); });
    get('items/index.html', 'ItemController@getItemsIndexView');
    get('items/item.html', 'ItemController@getItemView');
    get('messages/messages.html', 'MessageController@getMessagesView');
    get('messages/inbox.html', 'MessageController@getInboxView');
    get('messages/conversation.html', 'MessageController@getConversationView');
    get('messages/conversation_delivered.html', 'MessageController@getConversationDeliveredView');
    get('messages/conversation_inbox.html', 'MessageController@getConversationInboxView');
    get('messages/create.html', 'MessageController@getCreateView');
});

Route::group(['prefix' => 'search'], function()
{
    Route::get('user/{search?}', ['as' => 'search.user', 'uses' => 'SearchController@getUser']);
});

Route::group(['prefix' => 'me', 'middleware' => 'auth'], function()
{
    Route::get('/', ['as' => 'me', 'uses' => 'UserController@getUserInfo']);
    Route::get('friends', ['as' => 'me.friends', 'uses' => 'UserController@getFriendsList']);
    Route::group(['prefix' => 'settings'], function()
    {
        Route::get('/', ['as' => 'settings.index', 'uses' => 'SettingsController@index']);
    });
    Route::group(['prefix' => 'products'], function()
    {
        Route::get('/', ['as' => 'products.index', 'uses' => 'ProductController@index']);
        Route::get('categories', ['as' => 'products.categories', 'uses' => 'CategoryController@getCategories']);
        Route::post('categories', ['as' => 'products.categories.post', 'uses' => 'CategoryController@postCategory']);
    });
    Route::group(['prefix' => 'messages'], function()
    {
        Route::get('/', ['as' => 'messages.index', 'uses' => 'MessageController@index']);

        Route::get('offset', ['as' => 'messages.offset', 'uses' => 'ThreadController@getMessagesByOffset']);
        Route::post('create', ['as' => 'messages.create', 'uses' => 'MessageController@createMessage']);

        Route::get('inquiries', ['as' => 'messages.inquiries', 'uses' => 'ThreadController@getAllInquiries']);
        Route::get('inquire/{thread_id}', ['as' => 'messages.inquiries.thread', 'uses' => 'ThreadController@getInquiryByThread']);
        Route::get('inquire/{thread_id}/messages', ['as' => 'messages.inquiries.thread.messages', 'uses' => 'ThreadController@getMessagesByThread']);
        Route::post('inquire/reply', ['as' => 'messages.inquiries.reply', 'uses' => 'MessageController@replyToInquire']);
        Route::post('inquire/delivered', ['as' => 'messages.inquiries.delivered', 'uses' => 'ThreadController@updateToDelivered']);

        Route::get('delivered', ['middleware' => 'admin', 'as' => 'messages.delivered', 'uses' => 'ThreadController@getAllDelivered']);

        Route::get('inbox', ['as' => 'messages.inbox', 'uses' => 'ThreadController@getAllInboxes']);
    });
});

Route::group(['prefix' => 'items'], function()
{
    Route::get('/', ['as' => 'items.index', 'uses' => 'ItemController@index']);
    Route::get('/{id}', ['as' => 'items.item', 'uses' => 'ItemController@show']);
    Route::get('/category/{id}', ['as' => 'items.category', 'uses' => 'ItemController@showByCategory']);
});

Route::group(['prefix' => 'item'], function()
{
    get('/{id}', ['as' => 'item.show', 'uses' => 'ItemController@show']);
    post('/inquire', ['middleware' => 'auth', 'as' => 'item.inquire', 'uses' => 'MessageController@inquireItem']);
});


// Route::resource( 'category', 'CategoryController' );
