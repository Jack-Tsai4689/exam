<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Route::auth();
Route::get('/', "HomeController@main");
//Route::get('/home', 'HomeController@index');
Route::get('/logout', "HomeController@logout");
Route::get('/login', "HomeController@index");
Route::post('/login', 'HomeController@login');

Route::group(['prefix'=>'exam'], function(){
	Route::get('/', "ExamController@index");
	Route::post('/init', "ExamController@init_check");
	Route::get('/info', "ExamController@goexam");
});

//Route::resource('/sets', "SetsController");
Route::group(['prefix'=>'sets'], function(){
	Route::get('/', "SetsController@index");
	Route::get('create', "SetsController@create");
	Route::post('/', "SetsController@store");
	Route::get('{id}/edit', "SetsController@edit");
	Route::delete('{id}', "SetsController@destroy");
	Route::put('{id}', "SetsController@update");
	//開放考試
	Route::put('{id}/finish', "SetsController@status_change");
	//預覽
	Route::get('{id}/show', "SetsController@show");
	//ajax更新大題
	Route::post('{id}/subu', "SetsController@ajstore_part");
	//ajax編題大題
	Route::get('{id}/subshow', "SetsController@ajedit_part");
	//ajax更新大題順序
	Route::post("{id}/upsort", "SetsController@ajupdate_psort");
	//ajax加入題目
	Route::post('{id}/joinq', "SetsController@partjoinque");
	//ajax讀取大題題目
	Route::get("{id}/part", "SetsController@ajshow_que");
	//ajax更新大題題目順序
	Route::post("{id}/usort", "SetsController@ajupdate_sortq");
	//ajax刪除大題題目
	Route::delete("{id}/que", "SetsController@ajdelete_que");
});

Route::group(['prefix'=>'ques'], function(){
	Route::get('/', "QueController@index");
	Route::get('/create', "QueController@create");
	Route::post('/', "QueController@store");
	Route::get('{id}/edit', "QueController@edit");
	Route::put('{id}',"QueController@update");
	Route::get('/imp', "QueController@join");
});

Route::group(['prefix'=>'know'], function(){
	Route::get('/', "KnowledgeController@index");
	Route::get('create', "KnowledgeController@create");
	Route::post('/', "KnowledgeController@store");
	Route::get('{id}/edit', "KnowledgeController@edit");
	Route::delete('{id}', "KnowledgeController@destroy");
	Route::put('{id}', "KnowledgeController@update");
	Route::get('join', "KnowledgeController@join");
});

Route::get('basic', "BasicController@index");
Route::post('basic', "BasicController@store");
Route::get('basic/detail', "BasicController@ajshow");




