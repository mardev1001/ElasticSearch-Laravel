<?php

Route::get('/', 'HomeController@index');

Route::get('/formwrapper', function () {
    return view('formWrapper');
});

//Route::auth();

//Route::get('/home', 'HomeController@index');
    
Route::group(['middleware' => ['web']], function () {
    Route::auth();
    Route::resource('dokumente', 'DocumentController');
    Route::resource('iso-dokumente', 'IsoDocumentController');
    Route::resource('rundschreiben', 'RundschreibenDocumentController');
    Route::resource('rundschreiben-qmr', 'RundschreibenDocumentController');
    Route::resource('rundschreiben-news', 'RundschreibenDocumentController');
    Route::resource('vorlagedokumente', 'VorlageDocumentController');
    Route::resource('dokument-typen', 'DocumentTypeController');
    Route::resource('iso-kategorien', 'IsoCategoryController');
    Route::resource('mandanten', 'MandantController');
    Route::resource('benutzer', 'UserController');
    Route::resource('rollen', 'RoleController');
    Route::resource('adressaten', 'AdressatController');
    Route::resource('favoriten', 'FavoritesController');
    Route::resource('historie', 'HistoryController');
    Route::resource('statistik', 'StatsController');
    Route::resource('telefonliste', 'TelephoneListController');
    Route::resource('einstellungen', 'SettingsController');
    Route::resource('suche', 'SearchController');
}); //end web middleware
