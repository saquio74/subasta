<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\users;

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

Route::middleware('auth:api')->get('/user',             [users::class,'user']);
Route::middleware('auth:api')->put('/update',           [users::class,'userDataModify']);



Route::prefix('auth')->group(function(){
    Route::post('login',        [users::class,'login']);
    Route::post('signup',       [users::class,'signup']);
    Route::get('email/verify/{id}/{code}',  [users::class,'verify']);
    Route::post('resend',       [users::class,'resend']);
});