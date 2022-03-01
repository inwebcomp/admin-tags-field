<?php

namespace Admin\Fields\Tags;

use Illuminate\Support\ServiceProvider;
use InWeb\Admin\App\Admin;
use InWeb\Admin\App\AdminRoute;
use InWeb\Admin\App\Events\ServingAdmin;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Admin::serving(function (ServingAdmin $event) {
            Admin::script('tags-field', __DIR__.'/../dist/js/field.js');
            Admin::style('tags-field', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        AdminRoute::api('\Admin\Fields\Tags', function () {
            $this->registerRoutes();
        });
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
