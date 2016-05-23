<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\IsoCategory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
          //view()->composer('master', 'App\Http\ViewComposers\MasterViewComposer');
          view()->composer('formWrapper', 'App\Http\ViewComposers\FormViewComposer');
          
          $isoCategories = IsoCategory::all();
          view()->share(compact('isoCategories'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
