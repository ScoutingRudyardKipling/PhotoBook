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

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;

Auth::routes(
    [
        'register' => config('auth.register'),
        'reset'    => false,
        'verify'   => false,
    ]
);
if (config('auth.useSol')) {
    Route::post('auth/snl-login', [LoginController::class, 'snlLogin'])->name('login.snl');
    Route::get('auth/snl-login', [LoginController::class, 'snlLogin'])->name('login.snl');
}
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::group(
    ['middleware' => ['auth']],
    function () {
        Route::resource(
            'album',
            AlbumController::class,
            [
                'except' => [
                    'index',
                    'create',
                ],
            ]
        );
        Route::get('album/{parent}/subalbum/create', [AlbumController::class, 'create'])->name('album.create');
        Route::get('album/{album}/content/upload', [ContentController::class, 'uploadView'])->name('content.upload');
        Route::post('content/upload/action', [UploadController::class, 'uploadAjax']);
        Route::resource(
            'content',
            ContentController::class,
            [
                'except' => [
                    'index',
                ],
            ]
        );
        Route::get('/media/{filePath}', [MediaController::class, 'get'])
            // @mark I updated the configuration of the cache control into config/media-libary:41 . this should also work. please test
    //            ->middleware('cache.headers:private;max_age=63072001;immutable')
            ->where('filePath', '.*')
            ->name('media.get');
        Route::resource(
            'user',
            UserController::class
        );
    }
);
