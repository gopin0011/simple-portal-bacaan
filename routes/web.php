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

Route::get('/',function() {
    return redirect()->route('blog.liputan6');
 });

Route::get('image/{filename}', function ($filename)
{
    return Image::make(storage_path('app/public/' . $filename))->response();
})->name('image.path');

Route::get('/liputan6/indeks', 'HomeController@liputan6Index')->name('blog.liputan6');
Route::get('/blog/read', 'HomeController@blogRead')->name('blog.read');
Route::get('/masak-apa-hari-ini', 'HomeController@masakApaHariIni')->name('blog.masakApaHariIni');
