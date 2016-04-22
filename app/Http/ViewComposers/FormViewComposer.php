<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Request;

class FormViewComposer
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
        $params = '';
        $action = 'store';
        $method = 'post';
        $form = '';
        $controller ='TESTING123321';
        //this is the real view composer
        $view->with('controller',$controller );
       
        
    }
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
     protected function detectMethod(){
        $action = 'store';
        $method = 'post';
        $form = '';
        $button = 'save';
        $fileUpload  = '';
        if( Request::is('*/edit') ){
            $action = 'post';
            $method = 'patch';
          trans('controller.edit');// EDIT controller
         
        } 
        elseif( Request::is('*/search') ){
            
        }
     }
}