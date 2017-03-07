<?php

// Route::get('/formwrapper', function () {
//     return view('formWrapper');
// });

//Route::get('/home', 'HomeController@index');

//Route::group(['middleware' => ['web']], function () {

Route::auth();

Route::group(array('middleware' => ['auth']), function () {
    Route::get('/', 'HomeController@index');
    Route::get('kontakt', 'HomeController@contact');
    Route::post('kontakt', 'HomeController@contactSend');
    Route::get('kontaktanfragen', 'HomeController@contactIndex');
    Route::get('kontaktanfragen/suche', 'HomeController@contactSearch');
    // Route::post('kontaktanfragen/suche', 'HomeController@contactSearch');
    Route::get('tipps-und-tricks', 'HomeController@tipsAndTricks');
    Route::get('neptun-verwaltung', 'HomeController@neptunManagment');

    Route::get('/download/{path_part_one}/{path_part_two}', 'HomeController@download');
    Route::get('/open/{path_part_one}/{path_part_two}', 'HomeController@open');

    Route::post('dokumente/authorize/{id}', 'DocumentController@authorizeDocument');
    Route::get('anhang-delete/{document_id}/{editor_id}/{editor_document_id}', 'DocumentController@destroyByLink');
    Route::any('dokumente/suche', 'DocumentController@search');
    // Route::get('dokumente/suchergebnisse', 'DocumentController@searchResults');
    Route::get('dokumente/rundschreiben', 'DocumentController@rundschreiben');
    // Route::any('dokumente/rundschreiben/suche', 'DocumentController@search');
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
    Route::post('iso-dokumente/delete/{id}', 'DocumentController@deleteIsoCategoriesById');
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
    Route::get('dokumente/{id}/pdf', 'DocumentController@generatePdf');
    Route::get('dokumente/ansicht/{id}/{variant_id}', 'DocumentController@previewDocument');
    Route::get('dokumente/ansicht-pdf/{id}/{variant_id}', 'DocumentController@generatePdfPreview');
    Route::get('papierkorb', 'DocumentController@indexTrash');
    Route::get('papierkorb/download/{id}', 'DocumentController@downloadTrash');
    Route::post('papierkorb/manage', 'DocumentController@destroyTrash');
    Route::resource('dokumente', 'DocumentController'); //documente editor in CRUD
    Route::post('comment/{id}', 'DocumentController@saveComment');
    Route::get('comment-delete/{comment_id}/{document_id}', 'DocumentController@deleteComment');

    Route::get('juristenportal/notiz', 'JuristenPortalController@notiz');
    Route::get('juristenportal/upload', 'JuristenPortalController@uploadView');
    Route::post('juristenportal/upload', ['as' => 'juristenportal.upload', 'uses' => 'JuristenPortalController@upload']);
    Route::resource('juristenportal', 'JuristenPortalController');

    // Route::post('mandanten/{id}/user-role', 'MandantController@createInternalMandantUser');
    Route::get('mandanten/ajax-internal-roles', 'MandantController@ajaxInternalRoles');
    Route::post('mandanten/ajax-internal-roles', 'MandantController@ajaxInternalRoles');
    Route::post('mandanten/{id}/internal-roles', ['as' => 'mandant.internal-roles-add', 'uses' => 'MandantController@createInternalMandantUser']);
    Route::post('mandanten/{id}/internal-roles-edit', ['as' => 'mandant.internal-roles-edit', 'uses' => 'MandantController@editInternalMandantUser']);
    Route::post('mandanten/user-delete', 'MandantController@destroyMandantUser');
    Route::get('mandanten/search', 'MandantController@search');
    Route::post('mandanten/search', 'MandantController@search');
    Route::post('mandanten/search-single', 'MandantController@searchSingle');
    Route::patch('mandanten/activate', 'MandantController@mandantActivate');
    Route::get('mandantenverwaltung', 'MandantController@clientManagment');
    Route::get('mandanten/export', 'MandantController@xlsExport');
    Route::post('mandanten/export/xls', 'TelephoneListController@xlsExport');
    Route::resource('mandanten', 'MandantController');

    Route::get('benutzer/create-partner', ['as' => 'benutzer.create-partner', 'uses' => 'UserController@createPartner']);
    Route::post('benutzer/create-partner/store', ['as' => 'benutzer.create-partner-store', 'uses' => 'UserController@createPartnerStore']);
    Route::post('benutzer/create-partner-roles/store', ['as' => 'benutzer.partner-roles-store', 'uses' => 'UserController@createPartnerRolesStore']);
    Route::get('benutzer/standard-benutzer', 'UserController@defaultUser');
    Route::post('benutzer/standard-benutzer/save', 'UserController@defaultUserSave');
    Route::get('benutzer/profil', 'UserController@profile');
    Route::get('benutzer/{id}/partner/edit', 'UserController@editPartner');
    // Route::match(['post', 'get'], 'benutzer/{id}/partner/{mandant_id}/edit', 'UserController@editPartner');
    Route::post('benutzer/profil', 'UserController@saveProfile');
    Route::post('benutzer/role-transfer', 'UserController@userRoleTransfer');
    Route::post('benutzer/roles-add', 'UserController@userMandantRoleAdd');
    Route::patch('benutzer/roles-edit', 'UserController@userMandantRoleEdit');
    Route::patch('benutzer/roles-edit-partner', 'UserController@userMandantRoleEditPartner');
    Route::patch('benutzer/activate', 'UserController@userActivate');
    Route::post('benutzer/user-delete', 'UserController@destroyMandantUser');
    // Route::get('benutzer/{id}/edit/deleted', 'UserController@editDeleted');

    // UserController@index is used as an individual mandant link becouse of the name-ing
    Route::resource('benutzer', 'UserController');
    Route::resource('iso-kategorien', 'IsoCategoryController');
    Route::resource('juristenportal-kategorien', 'JuristenPortalCategoryController');
    Route::resource('rollen', 'RoleController');
    Route::resource('empfangerkreis ', 'AdressatController');
    Route::resource('adressaten', 'AdressatController');
    Route::get('dokument-typen/sort-up/{id}', 'DocumentTypeController@sortUp');
    Route::get('dokument-typen/sort-down/{id}', 'DocumentTypeController@sortDown');
    Route::resource('dokument-typen', 'DocumentTypeController');

    // Favorites routes
    Route::get('dokumente/{id}/favorit', 'DocumentController@favorites');
    Route::get('favoriten/category/{id}/delete', 'FavoritesController@destroyCategory');
    Route::get('favoriten/kategorieverwaltung', 'FavoritesController@editCategories');
    Route::post('favoriten/kategorieverwaltung/store', 'FavoritesController@storeFavoriteCategory');
    Route::patch('favoriten/kategorieverwaltung/update', 'FavoritesController@updateFavoriteCategory');
    Route::resource('favoriten', 'FavoritesController');

    // Route::resource('historie', 'HistoryController');
    // Route::resource('statistik', 'StatsController');
    Route::get('telefonliste/{id}/pdf', 'TelephoneListController@pdfExport');
    Route::post('telefonliste/display-options', 'TelephoneListController@displayOptions');
    Route::resource('telefonliste', 'TelephoneListController');
    Route::resource('einstellungen', 'SettingsController');
    Route::get('suche/erweitert', 'SearchController@searchAdvanced');
    Route::get('suche/telefonliste', 'SearchController@searchPhoneList');
    Route::post('suche/telefonliste', 'SearchController@searchPhoneList');
    Route::resource('suche', 'SearchController');

    // Wiki routes
    Route::post('wiki-kategorie/suche', 'WikiCategoryController@search');
    Route::resource('wiki-kategorie', 'WikiCategoryController');
    Route::any('wiki/suche', 'WikiController@search');
    Route::get('wiki/verwalten', 'WikiController@managmentUser');
    Route::get('wiki/duplicate/{id}', 'WikiController@duplicate');
    Route::post('wiki/verwalten', 'WikiController@searchManagment');
    Route::post('wiki/verwalten-admin', 'WikiController@searchManagment');
    Route::get('wiki/{id}/activate', 'WikiController@wikiActivation');
    Route::get('wiki/verwalten-admin', 'WikiController@managmentAdmin');
    Route::resource('wiki', 'WikiController');

    // inventory routes
    Route::get('inventarliste/abrechnen', 'InventoryController@abrechnen');
    Route::get('inventarliste/abrechnen-abgerechnt', 'InventoryController@abrechnenAbgerechnt');

    Route::get('inventarliste/abrechnen-alle', 'InventoryController@abrechnenAlle');
    // Route::get('inventarliste/abrechnen/','InventoryController@abrechnen');
    Route::post('inventarliste/abrechnen/{id}/update', 'InventoryController@updateAbrechnen');
    Route::get('inventarliste/suche-abrechnen', function () {
        return redirect('inventarliste/abrechnen');
    });
    Route::post('inventarliste/suche-abrechnen', 'InventoryController@searchAbrechnen');
    Route::get('inventarliste/suche-abrechnen-abgerechnt', function () {
        return redirect('inventarliste/abrechnen-abgerechnt');
    });
    Route::post('inventarliste/suche-abrechnen-abgerechnt', 'InventoryController@searchAbrechnenAbgerechnt');

    Route::get('inventarliste/suche-abrechnen-alle', function () {
        return redirect('inventarliste/abrechnen-alle');
    });
    Route::post('inventarliste/suche-abrechnen-alle', 'InventoryController@searchAbrechnenAlle');
    Route::post('inventarliste/abrechnen/pdf', 'InventoryController@abrechnenPdf');

    Route::get('inventarliste/kategorien', 'InventoryController@categories');
    Route::post('inventarliste/kategorien', 'InventoryController@postCategories');
    Route::post('inventarliste/kategorien/{id}/update', 'InventoryController@updateCategories');
    Route::get('inventarliste/destroy-category/{id}', 'InventoryController@destroyCategory');
    Route::get('inventarliste/destroy-size/{id}', 'InventoryController@destroySize');
    Route::get('inventarliste/historie/{id}', 'InventoryController@history');
    Route::post('inventarliste/sizes', 'InventoryController@postSizes');
    Route::post('inventarliste/sizes/{id}/update', 'InventoryController@updateSizes');
    Route::get('inventarliste/groessen', 'InventoryController@sizes');
    Route::get('inventarliste/suche', 'InventoryController@search');
    //Route::post('inventarliste/suche', 'InventoryController@search');
    Route::resource('inventarliste', 'InventoryController');

    // Developer Routes
    // Route::get('dev/sandbox', 'DocumentTypeController@devSandbox');
}); //end auth middleware

//}); //end web middleware
