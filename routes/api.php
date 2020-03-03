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
Route::get('/hello', function (Request $request) {
   return ['2'];
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', 'OAuth\PasswordGrantLoginController@login');
Route::post('/login/refresh', 'OAuth\PasswordGrantLoginController@refresh');

Route::middleware('auth:api')->post('/logout', 'OAuth\PasswordGrantLoginController@logout');