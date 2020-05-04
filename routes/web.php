<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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

Route::get('/', function () {
    return redirect(\route('files.index'));
});

// Route for upload e download files.....

Route::resource('files','DocumentController');
Route::get('delete/{file}', 'DocumentController@delete')->name('delete.files');
Route::get('download/{document}','DocumentController@download')->name('download');
Route::get('table','DocumentController@table')->name('datatable');

Route::get('search', 'DocumentController@search')->name('search');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
