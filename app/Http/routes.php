<?php

// Route::get('/formwrapper', function () {
//     return view('formWrapper');
// });

//Route::get('/home', 'HomeController@index');
    
//Route::group(['middleware' => ['web']], function () {
    
Route::auth();
    
Route::group( array('middleware' => ['auth']), function(){
    
        Route::get('/', 'HomeController@index');
    
        Route::get('dokumente/iso-dokumente', 'DocumentController@isoDocument');
        Route::get('dokumente/rundschreiben', 'DocumentController@rundschreiben');
        Route::get('dokumente/rundschreiben-pdf', 'DocumentController@rundschreibenPdf');
        Route::get('dokumente/rundschreiben-qmr', 'DocumentController@rundschreibenQmr');
        Route::get('dokumente/rundschreiben-news', 'DocumentController@rundschreibenNews');
        Route::get('dokumente/vorlagedokumente', 'DocumentController@documentTemplates');
        Route::get('dokumente/dokument-typen', 'DocumentController@documentType');
        Route::get('dokumente/iso-kategorien', 'DocumentController@isoCategories');
        //Route::get('dokumente/anlegen', 'DocumentController@anlegen');//editor
        Route::get('dokumente/datei-upload', 'DocumentController@documentUpload');
        Route::get('dokumente/statistik/{id}', 'DocumentController@documentStats');
        Route::get('dokumente/historie/{id}', 'DocumentController@documentHistory');
        Route::post('document-upload', 'DocumentController@upload');
        
        Route::get('dokumente/rechte-und-freigabe/{id}', 'DocumentController@anlegenRechteFreigabe');//document id
        Route::post('dokumente/rechte-und-freigabe/{id}', 'DocumentController@saveRechteFreigabe');
        Route::get('dokumente/pdf-upload/{id}/edit', 'DocumentController@editPdfUpload');
        Route::post('pdf-upload', 'DocumentController@pdfUpload');
        Route::resource('dokumente', 'DocumentController');//documente editor in CRUD
        
        Route::post('mandanten/generate-user-role', 'MandantController@generateUserRole');
        Route::post('mandanten/search', 'MandantController@search');
        Route::resource('mandanten', 'MandantController');
        
        Route::get('benutzer/profile', 'UserController@profile');
        Route::post('benutzer/profile', 'UserController@saveProfile');
        Route::post('benutzer/role-transfer', 'UserController@userRoleTransfer');
        Route::post('benutzer/roles-add', 'UserController@userMandantRoleAdd');
        Route::patch('benutzer/roles-edit', 'UserController@userMandantRoleEdit');
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
        Route::post('suche/telefonliste', 'SearchController@searchPhoneList');
        Route::resource('suche', 'SearchController');
        

    
}); //end auth middleware
    
//}); //end web middleware
