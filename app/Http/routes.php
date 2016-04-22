<?php

Route::get('/', function(){
    return view('welcome');
});

Route::get('/formwrapper', function () {
    return view('formWrapper');
});

Route::auth();

Route::get('/home', 'HomeController@index');
