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

// 最初のページはログイン完了しました！の画面(ただし、その画面はユーザー認証ありきなので、ログイン画面に飛びます)
Route::get('/', 'HomeController@index')->name('root');

// ユーザー認証
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

// 商品関連のルートをまとめる
// groupを使うことで、urlにproductがついているものをまとめられます
Route::group(['prefix' => 'product'], function (){
    // 一覧画面の表示
    Route::get('lineup', 'ProductController@showLineup')->name('product.lineup');
    // 登録画面の表示
    Route::get('create', 'ProductController@showCreate')->name('product.create');
    // 商品情報を登録
    Route::post('store', 'ProductController@exeStore')->name('product.store');
    // 詳細画面の表示
    Route::get('{id}', 'ProductController@showDetail')->name('product.detail');
    // 編集画面の表示
    Route::get('edit/{id}', 'ProductController@showEdit')->name('product.edit');
    // 商品情報を更新
    Route::post('update', 'ProductController@exeUpdate')->name('product.update');
    // 商品情報を削除
    Route::post('delete/{id}', 'ProductController@exeDelete')->name('product.delete');
});


