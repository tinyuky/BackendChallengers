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
    Route::post('addAccount','AdminController@add');
    Route::post('updateAccount','AdminController@update');
    Route::get('getAllAccounts', 'AdminController@getall');
    Route::get('getAccount/{id}','AdminController@get');
});

Route::group([
    'prefix' => 'assistant',
    'middleware'=>'auth.jwtass'
],function($router){
    Route::post('handleFile','StudentController@handle');    
    Route::get('importFile/{name}','StudentController@import'); 
    Route::get('exportTem', 'StudentController@export');

});


