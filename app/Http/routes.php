<?php

Route::get('/', function(){
    return view('welcome');
});

Route::get('/formwrapper', function () {
    return view('formWrapper');
});

Route::auth();

Route::get('/home', 'HomeController@index');
    
Route::group(['middleware' => ['web']], function () {
    Route::auth();
    Route::resource('dokumente', 'DocumentController');
    Route::resource('iso-kategorien', 'DocumentController');
    Route::resource('mandanten', 'MandantController');
    Route::resource('benutzer', 'UserController');
    Route::resource('rollen', 'RoleController');
    Route::resource('adressaten', 'AdressatController');
    Route::resource('favoriten', 'FavoritesController');
    Route::resource('historie', 'HistoryController');
    Route::resource('statistik', 'StatsController');
    Route::resource('einstellungen', 'SettingsController');
    Route::resource('suche', 'SearchController');
}); //end web middleware
