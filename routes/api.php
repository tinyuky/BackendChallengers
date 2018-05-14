<?php

use Illuminate\Http\Request;
Route::group([
    'prefix' => 'auth'
],function($router){    
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('me', 'AuthController@me');
});

Route::group([
    'prefix' => 'admin',
    'middleware'=>'auth.jwtad'
],function($router){    
    Route::post('addaccount','AdminController@add');
    Route::post('updateaccount','AdminController@update');
    Route::get('getallaccounts', 'AdminController@getall');
    Route::get('getaccount/{id}','AdminController@get');
});


