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
// Route::get('/sets', "SetsController@index");
// Route::get('/sets/create', "SetsController@create");
// Route::post('/sets', "SetsController@store");
// Route::get('/sets/{id}/edit', "SetsController@edit");
// Route::delete('/sets/{id}', "SetsController@destroy");
// Route::put('/sets/{id}', "SetsController@update");

Route::group(['prefix'=>'sets'], function(){
	Route::get('/', "SetsController@index");
	Route::get('create', "SetsController@create");
	Route::post('/', "SetsController@store");
	Route::get('{id}/edit', "SetsController@edit");
	Route::delete('{id}', "SetsController@destroy");
	Route::put('{id}', "SetsController@update");
});

Route::get('/ques', "QueController@index");
Route::get('/ques/create', "QueController@create");
Route::post('/ques', "QueController@store");

Route::get('/know', "KnowledgeController@index");
Route::get('/know/create', "KnowledgeController@create");
Route::post('/know', "KnowledgeController@store");

Route::get('basic', "BasicController@index");
Route::post('basic', "BasicController@store");
Route::get('basic/detail', "BasicController@ajshow");




