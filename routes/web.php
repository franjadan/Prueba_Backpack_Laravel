<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function(){
    return view('posts.index');
});

Route::get('/posts', 'App\Http\Controllers\PostController@getPosts');

Route::get('/post/{post}/ver', function(\App\Models\Post $post){
    return view('posts.show', [
        'post' => $post,
    ]);
});

Route::get('/post/{id}', 'App\Http\Controllers\PostController@getPost');

Route::get('/comentarios/{id}', 'App\Http\Controllers\CommentController@getComments');

Auth::routes();
