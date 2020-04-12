<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|

 All routes coming with a default resource route
Verb          Path                        Action  Route Name
GET           /users                      index   users.index
GET           /users/create               create  users.create
POST          /users                      store   users.store
GET           /users/{user}               show    users.show
GET           /users/{user}/edit          edit    users.edit
PUT|PATCH     /users/{user}               update  users.update
DELETE        /users/{user}               destroy users.destroy

*/

Auth::routes(
    [
        'register' => config('auth.register'),
        'reset'    => false,
        'verify'   => false,
    ]
);

Route::post('auth/snl-login', 'Auth\\LoginController@snlLogin')->name('login.snl');
Route::get('auth/snl-login', 'Auth\\LoginController@snlLogin')->name('login.snl');

Route::get('/', 'HomeController@index')->name('home');
Route::group(
    ['middleware' => ['auth']],
    function () {
        Route::resource(
            'album',
            'AlbumController',
            [
                'except' => [
                    'index',
                ],
            ]
        );
        Route::get('album/{album}/content/upload', 'ContentController@uploadView')->name('content.upload');
        Route::post('content/upload/action', 'ContentController@uploadAjax');
        Route::resource(
            'content',
            'ContentController',
            [
                'except' => [
                    'index',
                ],
            ]
        );
        Route::get('/media/{filePath}', 'MediaController@get')
            ->where('filePath', '.*')
            ->name('media.get');
    }
);
