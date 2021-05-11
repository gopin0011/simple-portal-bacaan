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

Route::redirect('/', '/liputan6/indeks');

// Route::get('/', 'HomeController@index', function () {
//     return view('welcome');
// })->name('home.index');

Route::get('/publicholiday', function(){
    return view('publicholiday');
});


Route::get('image/{filename}', function ($filename)
{
    return Image::make(storage_path('app/public/' . $filename))->response();
})->name('image.path');

Route::get('/liputan6/indeks', 'HomeController@liputan6Index')->name('home.liputan6');
Route::get('/nasional', 'HomeController@cnnNasional')->name('home.nasional');
