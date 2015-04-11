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

Route::get('routes', 'HomeController@getAllRoutes');

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
    get('messages/conversation.html', 'MessageController@getConversationView');
    get('messages/conversation_delivered.html', 'MessageController@getConversationDeliveredView');
    get('messages/create.html', 'MessageController@getCreateView');

	// INQUIRIES
	get('messages/inquiries.html', 'InquiryController@getMessagesView');
	get('messages/conversation_inquiry.html', 'InquiryController@getConversationView');

    // INBOX
    get('messages/inbox.html', 'InboxController@getMessagesView');
    get('messages/conversation_inbox.html', 'InboxController@getConversationView');

    // DELIVER
    get('messages/deliver.html', 'DeliverController@getMessagesView');
    get('messages/conversation_deliver.html', 'DeliverController@getConversationView');

	get('settings/newsletter.html', 'SettingsController@getNewsletterView');
});

Route::group(['prefix' => 'search'], function()
{
    Route::get('user/{search?}', ['as' => 'search.user', 'uses' => 'SearchController@getUser']);
	Route::get('product/{search?}', ['as' => 'search.product', 'uses' => 'SearchController@getProduct']);
});

Route::post('newsletter', ['as' => 'newsletter', 'uses' => 'NewsletterController@subscribeToNewsletter']);
Route::get('newsletter', ['as' => 'newsletter.user', 'uses' => 'NewsletterController@getSubscribeByUser']);

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
		Route::group(['prefix' => 'inquiry'], function()
		{
			get('all', ['as' => 'inquiry.all', 'uses' => 'InquiryController@getAll']);
			get('{inquiry_id}', ['as' => 'inquiry', 'uses' => 'InquiryController@get']);
			get('{inquiry_id}/conversations', ['as' => 'inquiry.conversations', 'uses' => 'InquiryController@getConversations']);
			post('reply', ['as' => 'inquiry.reply', 'uses' => 'InquiryController@postReply']);
			post('delivered', ['as' => 'inquiry.delivered', 'uses' => 'DeliverController@create']);
		});
        Route::group(['prefix' => 'inbox'], function()
        {
            get('/', ['as' => 'messages.inbox', 'uses' => 'InboxController@getAll']);
            get('all', ['as' => 'inbox.all', 'uses' => 'InboxController@getAll']);
            get('{inbox_id}', ['as' => 'inbox', 'uses' => 'InboxController@get']);
            get('{inbox_id}/conversations', ['as' => 'inbox.conversations', 'uses' => 'InboxController@getConversations']);
            post('reply', ['as' => 'inbox.reply', 'uses' => 'InboxController@reply']);
            post('create', ['as' => 'inbox.create', 'uses' => 'InboxController@create']);
        });
	    Route::group(['prefix' => 'delivered'], function()
	    {
		    get('/', ['as' => 'messages.delivered', 'uses' => 'DeliverController@getAll']);
		    get('all', ['as' => 'delivered.all', 'uses' => 'DeliverController@getAll']);
		    get('{delivered_id}', ['as' => 'delivered', 'uses' => 'DeliverController@get']);
		    get('{delivered_id}/conversations', ['as' => 'delivered.conversations', 'uses' => 'DeliverController@getConversations']);
		    post('reply', ['as' => 'delivered.reply', 'uses' => 'DeliverController@reply']);
	    });
        Route::get('inquiries', ['as' => 'messages.inquiries', 'uses' => 'InquiryController@getAll']);
        // Route::get('inquire/{thread_id}', ['as' => 'messages.inquiries.thread', 'uses' => 'ThreadController@getInquiryByThread']);
        // Route::get('inquire/{thread_id}/messages', ['as' => 'messages.inquiries.thread.messages', 'uses' => 'ThreadController@getMessagesByThread']);
        // Route::post('inquire/reply', ['as' => 'messages.inquiries.reply', 'uses' => 'MessageController@replyToInquire']);
        // Route::post('inquire/delivered', ['as' => 'messages.inquiries.delivered', 'uses' => 'ThreadController@updateToDelivered']);

        //Route::get('delivered', ['middleware' => 'admin', 'as' => 'messages.delivered', 'uses' => 'ThreadController@getAllDelivered']);

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
    post('/inquire', ['middleware' => 'auth', 'as' => 'item.inquire', 'uses' => 'ConversationController@inquireItem']);
});


// Route::resource( 'category', 'CategoryController' );
