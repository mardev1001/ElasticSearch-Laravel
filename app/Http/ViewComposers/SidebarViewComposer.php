<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Request;

use App\DocumentType;
use App\IsoCategory;

class SidebarViewComposer
{
    
    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {   
        $view->with('documentTypes', DocumentType::all() );
        $view->with('isoCategories', IsoCategory::all() );
    }
    
   
}