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

//PHPREDI FOR LARAVEL -coonecting predis to phpredis
//composer.json add: "vetruvet/laravel-phpredis": "dev-master"

//Route::get('/', function () {
	//echo phpinfo();
	
	//PREDIS
	//$redis=app()->make('redis');
	//$redis->set('key1','test value');
	//return $redis->get("key1");
	
	//PHPREDIS 
	//$app=LRedis::connection();
	//predis
	/*$app=Redis::connection();
	$app->set('key2','test value 2');
	$app->get('key2'); 
	print_r($app->get('key2'));*/
	
	//print_r(app()->make('redis'));
   // return view('welcome');
//});

Route::get('/','WelcomeController@index');
Route::get('/article/{id}','BlogController@showArticle')->where('id','[0-9]+');
Route::get('/home','HomeController@index');

/*Route::controllers([
	'auth'=>'Auth\AuthController',
	'password'=>'Auth\PasswordController',
]);*/
