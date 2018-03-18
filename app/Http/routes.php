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
//考試
Route::group(['prefix'=>'exam'], function(){
	Route::get('/', "ExamController@index");
	//session初始化
	Route::post('/init', "ExamController@init_check");
	//測驗init 確認
	Route::get('/info', "ExamController@goexam");
	//開始測驗
	Route::post('/start', "ExamController@examing");
	//test
	//Route::get('/test/{id}', "ExamController@examtest");
	//存答案 &　下題題目
	Route::post('/', "ExamController@store");
	//redis test
	// Route::post('/podcast', "ExamController@test");
	//考試中離記錄
	Route::post('/quit', "ExamController@quit");
});

Route::group(['prefix'=>'score'], function(){
	//成績列表
	Route::get('/', "ScoreController@index");
	//個人成績
	Route::get('/{id}', "ScoreController@show");
});
//分析
Route::group(['prefix'=>'analy'], function(){
	//考題概念表
	Route::get('{id}', "AnalyController@source");
	//觀念比例圖
	Route::get('{id}/concept', "AnalyController@radar");
});
//發佈
Route::group(['prefix'=>'pub'], function(){
	//已發佈的記錄
	Route::get('/', "PubController@index");
	//設定頁
	Route::get('create', "PubController@create");
	//儲存頁
	Route::post('/', "PubController@store");
	//預覽
	Route::get('/{id}', "PubController@show");
	//ajax讀取大題題目
	Route::get("/{id}/part", "PubController@ajshow_que");
});
//考卷編輯
Route::group(['prefix'=>'sets'], function(){
	Route::get('/', "SetsController@index");
	Route::get('create', "SetsController@create");
	//Route::post('/', "SetsController@store");
	Route::post('/', "SetsController@store_set");

	//Route::get('{id}/edit', "SetsController@edit");
	Route::get('{id}/edit', "SetsController@edit_set");

	Route::delete('{id}', "SetsController@destroy");
	Route::put('{id}', "SetsController@update");
	//開放考試
	//Route::put('{id}/finish', "SetsController@status_change");
	//派卷用
	Route::get("/pfetch", "SetsController@ajpublish");
	//預覽
	Route::get('/{id}', "SetsController@show");
	//ajax更新大題
	Route::post('/{id}/subu', "SetsController@ajstore_part");
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
	//ajax 編輯考卷設定
	Route::get("{id}/structure", "SetsController@ajstru");

});
//題目
Route::group(['prefix'=>'ques'], function(){
	Route::get('/', "QueController@index");
	Route::get('/create', "QueController@create");
	Route::post('/', "QueController@store");
	Route::get('{id}/edit', "QueController@edit");
	Route::get('/imp', "QueController@join");
	Route::get('{id}',"QueController@show");
	Route::put('{id}',"QueController@update");
});
//知識點
Route::group(['prefix'=>'know'], function(){
	Route::get('/', "KnowledgeController@index");
	Route::get('create', "KnowledgeController@create");
	Route::post('/', "KnowledgeController@store");
	Route::get('{id}/edit', "KnowledgeController@edit");
	Route::delete('{id}', "KnowledgeController@destroy");
	Route::put('{id}', "KnowledgeController@update");
	Route::get('/join', "KnowledgeController@join");
});
//基本設定 類別、科目、章節
Route::get('basic', "BasicController@index");
Route::post('basic', "BasicController@store");
Route::get('basic/detail', "BasicController@ajshow");



