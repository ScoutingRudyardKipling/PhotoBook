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
*/

Auth::routes(
    [
        'register' => true,
        'reset'    => false,
        'verify'   => false,
    ]
);

Route::get('/', 'HomeController@index')->name('home');
Route::group(
    ['middleware' => ['auth']],
    function () {
        Route::resources(
            [
                'album'   => 'AlbumController',
                'content' => 'ContentController',
            ]
        );
        Route::get('/media/{filePath}', 'MediaController@get')
            ->where('filePath', '.*')
            ->name('media.get');
    }
);
