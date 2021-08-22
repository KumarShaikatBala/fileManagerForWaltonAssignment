<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::apiResource('users', 'API\UserController');
Route::apiResources([
    'user' => 'API\UserController'
]);
Route::group(['prefix' =>'folder'], function () {
    Route::get('index','API\FolderController@index');
    Route::post('store','API\FolderController@store');
    Route::put('{id}','API\FolderController@update');
    Route::delete('{id}','API\FolderController@destroy');
});
Route::group(['prefix' =>'file'], function () {
    Route::get('index','API\FileController@index');
    Route::post('store','API\FileController@store');
    Route::put('{id}','API\FileController@update');
    Route::delete('{id}','API\FileController@destroy');
});


Route::get('profile','API\UserController@profile');
Route::put('profile','API\UserController@updateProfile');
