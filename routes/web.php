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

Route::get('/', function () {
    return view('welcome');
});



// 図書一覧の表示
Route::get("library/index", "libraryController@index");

// 貸出フォーム
Route::get("library/borrow", "libraryController@borrowingForm");

//貸出処理
Route::post("library/borrow", "libraryController@borrow");

//返却処理
Route::post("library/return", "libraryController@returnBook");

//返却処理
Route::get("library/history", "libraryController@history");

Auth::routes();

Route::get("logout", function() {
    Auth::logout();
    return redirect("library/index");
});

Route::get('/home', 'HomeController@index')->name('home');

