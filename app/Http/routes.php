<?php

Route::get('/', 'HomeController@index');

Route::get('/formwrapper', function () {
    return view('formWrapper');
});

//Route::auth();

//Route::get('/home', 'HomeController@index');
    
Route::group(['middleware' => ['web']], function () {
    Route::auth();
    
    Route::get('dokumente/iso-dokumente', 'DocumentController@isoDocument');
    Route::get('dokumente/rundschreiben', 'DocumentController@rundschreiben');
    Route::get('dokumente/rundschreiben-pdf', 'DocumentController@rundschreibenPdf');
    Route::get('dokumente/rundschreiben-qmr', 'DocumentController@rundschreibenQmr');
    Route::get('dokumente/rundschreiben-news', 'DocumentController@rundschreibenNews');
    Route::get('dokumente/vorlagedokumente', 'DocumentController@documentTemplates');
    Route::get('dokumente/dokument-typen', 'DocumentController@documentType');
    Route::get('dokumente/iso-kategorien', 'DocumentController@isoCategories');
    Route::get('dokumente/anlegen', 'DocumentController@anlegen');
    Route::post('dokumente/anlegen', 'DocumentController@anlegenStore');
    Route::get('dokumente/anlegen/rechte-und-freigabe', 'DocumentController@anlegenRechteFreigabe');
    Route::get('dokumente/datei-upload', 'DocumentController@documentUpload');
    Route::get('dokumente/statistik/{id}', 'DocumentController@documentStats');
    Route::get('dokumente/historie/{id}', 'DocumentController@documentHistory');
    Route::resource('dokumente', 'DocumentController');
    
    Route::post('mandanten/generate-user-role', 'MandantController@generateUserRole');
    Route::resource('mandanten', 'MandantController');
    Route::resource('benutzer', 'UserController');
    Route::resource('rollen', 'RoleController');
    Route::resource('adressaten', 'AdressatController');
    Route::resource('dokument-typen', 'DocumentTypeController');
    Route::resource('iso-kategorien', 'IsoCategoryController');
    Route::resource('favoriten', 'FavoritesController');
    Route::resource('historie', 'HistoryController');
    Route::resource('statistik', 'StatsController');
    Route::resource('telefonliste', 'TelephoneListController');
    Route::resource('einstellungen', 'SettingsController');
    Route::get('suche/erweitert', 'SearchController@searchAdvanced');
    Route::resource('suche', 'SearchController');
}); //end web middleware
