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

//ログイン関連のルーティング
Auth::routes([
    'register' => false //ユーザ登録機能をオフにする（用意されている登録機能の停止）
]);
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// 全ユーザ
Route::group(['middleware' => ['auth','can:user-higher']], function(){
// ユーザ一覧
    Route::get('/account','AccountController@index')->name('account.index');
//マイページのルーティング
    Route::get('/mypage/{id}', 'MypageController@index' )->name('mypage.index');
    Route::get('/next_month/{id}/next/{month}', 'MypageController@next' )->name('mypage.next');
    Route::get('/before_month/{id}/before/{month}', 'MypageController@before' )->name('mypage.before');

    Route::get('/list', 'TimestampsController@list' )->name('list');
    Route::get('/timestamps_create', 'TimestampsController@create')->name('timestamps.create');
    Route::post('/timestamps_store/{id}', 'TimestampsController@store')->name('timestamps.store');
    Route::post('/timestamps_update/{id}', 'TimestampsController@update')->name('timestamps.update');

//勤務表をCSVで取り込むためのルーティング
    // インポート
    Route::get('/inport','AttendancesController@index')->name('inport');
    Route::post('/inport','AttendancesController@inport');
    // エクスポート
    Route::get('/export','AttendancesController@acquisition')->name('export');
    Route::post('/export','AttendancesController@export');

});

// 管理者以上
Route::group(['middleware' => ['auth', 'can:admin-higher']],function(){
    // ユーザ一覧
    Route::get('/administrator','AttendancesController@adminPage')->name('admin');

    // ユーザ登録
    Route::get('account/regist','AccountController@regist')->name('account.regist');
    Route::post('account/regist','AccountController@createData')->name('account.regist');
    // ユーザ編集
    Route::get('account/edit/{user_id}','AccountController@edit')->name('account.edit');
    Route::post('account/edit/{user_id}','AccountController@updateData')->name('account.edit');
    // ユーザ削除
    Route::post('/account/delete/{user_id}','AccountController@deleteData');
});

// システム管理者のみ
Route::group(['middleware' => ['auth', 'can:system-only']],function(){

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
