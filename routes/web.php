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

// Default page
Route::get('/', 'PagesController@home');

Route::get('/summoner', 'PagesController@summoner');

Route::post('/summoner/individualGameAjax', 'ajaxController@individualGameAjax');

	
	
// also ajax change later
Route::get('/summoner/champions', 'PagesController@summonerChampions');

Route::get('/summoner/champions/ajax/seasonRank', 'ajaxController@seasonRank');
Route::get('/summoner/champions/ajax/liveGame', 'ajaxController@summonerLiveGame');
Route::post('/summoner/champions/ajax/liveGameRunes','ajaxController@summonerLiveGameRune');

Route::get('/champions','PagesController@champions');

// Dynamic path for each champion
Route::get('/champions/{name}/statistics', 'PagesController@championsStat');

Route::get('/stats','PagesController@stats');

Route::get('/leaderboards','PagesController@leaderboards');