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

Route::namespace('Api')->group(function () {
    Route::get('user/all',  'FriendReqController@index');
    // Route::post('friend_req/send', 'FriendReqController@store');
    // Route::post('friend_req/approve', 'FriendReqController@approve');
    Route::post('friend_req/{action}', 'FriendReqController@store');
    Route::post('friend_req_list',  'FriendReqController@list');

    Route::post('friends_list',  'FriendsController@list');
    Route::post('friends_list/between',  'FriendsController@listbetween');
    Route::post('friends_list/block',  'FriendsController@block');
});