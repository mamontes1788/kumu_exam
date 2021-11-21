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

Route::prefix('/user')->group(function () {
    Route::post('/register', 'api\LoginController@store');
    Route::post('/login', 'api\LoginController@login');
});

Route::middleware('auth:api')
    ->get('/githubusers/{usernames}', 'api\GituserController@index');
