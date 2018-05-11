<?php

use Illuminate\Http\Request;
Route::group([
    'prefix' => 'admin',
    'middleware'=>'auth.jwtad'
],function($router){
    Route::post('logout', 'AdminController@logout');
    Route::post('add','AdminController@add');
    Route::post('update','AdminController@update');
    Route::get('getall', 'AdminController@getall');
    Route::get('get/{id}','AdminController@get');
    Route::delete('delete/{id}','AdminController@deactive');
});
Route::post('login', 'LoginController@login');
