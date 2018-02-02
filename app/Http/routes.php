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
Route::get('/', "SetsController@index");
//Route::get('/home', 'HomeController@index');
Route::get('/logout', "HomeController@logout");
Route::get('/login', "HomeController@index");
Route::post('/login', 'HomeController@login');

//Route::resource('/sets', "SetsController");
Route::group(['prefix'=>'sets'], function(){
	Route::get('/', "SetsController@index");
	Route::get('create', "SetsController@create");
	Route::post('/', "SetsController@store");
	Route::get('{id}/edit', "SetsController@edit");
	Route::delete('{id}', "SetsController@destroy");
	Route::put('{id}', "SetsController@update");
	Route::get('{id}/show', "SetsController@show");
	Route::post('{id}/subu', "SetsController@ajpart");
	
});

Route::group(['prefix'=>'ques'], function(){
	Route::get('/', "QueController@index");
	Route::get('/create', "QueController@create");
	Route::post('/', "QueController@store");
	Route::get('{id}/edit', "QueController@edit");
	Route::put('{id}',"QueController@update");
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




