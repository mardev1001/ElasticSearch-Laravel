<?php

// Route::get('/formwrapper', function () {
//     return view('formWrapper');
// });

//Route::get('/home', 'HomeController@index');
    
//Route::group(['middleware' => ['web']], function () {
    
Route::auth();
    
Route::group( array('middleware' => ['auth']), function(){
    
        Route::get('/', 'HomeController@index');
        
        Route::get('/download/{path_part_one}/{path_part_two}', 'HomeController@download');
        Route::get('/open/{path_part_one}/{path_part_two}', 'HomeController@open');
        
        Route::post('dokumente/authorize/{id}', 'DocumentController@authorizeDocument');
        Route::get('anhang-delete/{document_id}/{editor_id}/{editor_document_id}', 'DocumentController@destroyByLink');
        Route::any('dokumente/suche', 'DocumentController@search');
        // Route::get('dokumente/suchergebnisse', 'DocumentController@searchResults');
        Route::get('dokumente/rundschreiben', 'DocumentController@rundschreiben');
        Route::get('dokumente/rundschreiben-pdf', 'DocumentController@rundschreibenPdf');
        Route::get('dokumente/rundschreiben-qmr', 'DocumentController@rundschreibenQmr');
        Route::get('dokumente/news', 'DocumentController@rundschreibenNews');
        Route::get('dokumente/vorlagedokumente', 'DocumentController@documentTemplates');
        Route::get('dokumente/typ/{type}', 'DocumentController@documentType');
        Route::get('dokumente/datei-upload', 'DocumentController@documentUpload');
        Route::get('dokumente/statistik/{id}', 'DocumentController@documentStats');
        Route::get('dokumente/historie/{id}', 'DocumentController@documentHistory');
        Route::get('iso-dokumente', 'DocumentController@isoCategoriesIndex');
        Route::get('iso-dokumente/{slug}', 'DocumentController@isoCategoriesBySlug');
        Route::get('dokumente/editor/{id}/edit', 'DocumentController@editDocumentEditor');
        Route::post('editor', 'DocumentController@documentEditor');
        Route::get('dokumente/dokumente-upload/{id}/edit', 'DocumentController@editDocumentUpload');
        Route::post('document-upload', 'DocumentController@documentUpload');
        Route::get('dokumente/rechte-und-freigabe/{id}', 'DocumentController@anlegenRechteFreigabe'); //document id
        Route::post('dokumente/rechte-und-freigabe/{id}', 'DocumentController@saveRechteFreigabe');
        Route::get('dokumente/anlagen/{id}/{variant?}', 'DocumentController@attachments'); //document id
        Route::post('dokumente/anlagen/{id?}/{variant?}', 'DocumentController@saveAttachments');
        Route::get('dokumente/pdf-upload/{id}/edit', 'DocumentController@editPdfUpload');
        Route::post('pdf-upload', 'DocumentController@pdfUpload');
        Route::get('dokumente/new-version/{id}', 'DocumentController@newVersion');
        Route::get('dokumente/{id}/freigabe', 'DocumentController@freigabeApproval');
        Route::get('dokumente/{id}/activate', 'DocumentController@documentActivation');
        Route::get('dokumente/{id}/publish', 'DocumentController@publishApproval');
        Route::get('dokumente/{id}/favorit', 'DocumentController@favorites');
        Route::get('dokumente/{id}/pdf', 'DocumentController@generatePdf');
        Route::get('dokumente/ansicht/{id}/{variant_id}', 'DocumentController@previewDocument');
        Route::get('dokumente/ansicht-pdf/{id}/{variant_id}', 'DocumentController@generatePdfPreview');
        Route::resource('dokumente', 'DocumentController'); //documente editor in CRUD
        Route::post('comment/{id}', 'DocumentController@saveComment');
        
        // Route::post('mandanten/{id}/user-role', 'MandantController@createInternalMandantUser');
        Route::post('mandanten/{id}/internal-roles', ['as'=>'mandant.internal-roles-add', 'uses' => 'MandantController@createInternalMandantUser']);
        Route::post('mandanten/{id}/internal-roles-edit', ['as'=>'mandant.internal-roles-edit', 'uses' => 'MandantController@editInternalMandantUser']);
        Route::post('mandanten/user-delete', 'MandantController@destroyMandantUser');
        Route::post('mandanten/search', 'MandantController@search');
        Route::patch('mandanten/activate', 'MandantController@mandantActivate');
        Route::resource('mandanten', 'MandantController');
        
        Route::get('benutzer/profile', 'UserController@profile');
        Route::post('benutzer/profile', 'UserController@saveProfile');
        Route::post('benutzer/role-transfer', 'UserController@userRoleTransfer');
        Route::post('benutzer/roles-add', 'UserController@userMandantRoleAdd');
        Route::patch('benutzer/roles-edit', 'UserController@userMandantRoleEdit');
        Route::patch('benutzer/activate', 'UserController@userActivate');
        Route::resource('benutzer', 'UserController');
        
        Route::resource('wiki-kategorie', 'WikiCategoryController');
        
        Route::get('wiki/{id}/activate', 'WikiController@wikiActivation');
        Route::resource('wiki', 'WikiController');
        
        Route::resource('iso-kategorien', 'IsoCategoryController');
        Route::resource('rollen', 'RoleController');
        Route::resource('adressaten', 'AdressatController');
        Route::resource('dokument-typen', 'DocumentTypeController');
        Route::resource('favoriten', 'FavoritesController');
        // Route::resource('historie', 'HistoryController');
        Route::resource('statistik', 'StatsController');
        Route::resource('telefonliste', 'TelephoneListController');
        Route::resource('einstellungen', 'SettingsController');
        Route::get('suche/erweitert', 'SearchController@searchAdvanced');
        Route::post('suche/telefonliste', 'SearchController@searchPhoneList');
        Route::resource('suche', 'SearchController');

}); //end auth middleware
    
//}); //end web middleware
