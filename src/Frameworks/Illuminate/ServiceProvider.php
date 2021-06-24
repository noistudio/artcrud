<?php

namespace Artcrud\Frameworks\Illuminate;

use Artcrud\Commands\CreateTable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as Base;

/**
 * Class ServiceProvider
 * @package PrizyvaNet\Vault\Frameworks\Illuminate
 */
class ServiceProvider extends Base
{
    public function register()
    {

    }

    public function boot(){


        $this->loadRoutesFrom(__DIR__.'/../../routes/artcrud.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'artcrud');


        $this->publishes([
            __DIR__.'/../../resources/public' => public_path('vendor/artcrud'),
        ], 'artcrud');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/artcrud'),
        ],"artcrud");

        $this->publishes([
            __DIR__.'/../../config/artcrud.php' => config_path('artcrud.php'),
        ],"artcrud");

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateTable::class,
            ]);
        }
    }

}
