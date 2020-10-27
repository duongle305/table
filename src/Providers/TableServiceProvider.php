<?php


namespace DuoLee\Table\Providers;


use Config;
use DuoLee\Table\Supports\Builder;
use File;
use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    protected $module = 'table';

    public function boot()
    {
        $this->loadTranslationsFrom($this->getPath('resources/lang'), $this->module);
        $this->loadViewsFrom($this->getPath('resources/views'), $this->module);
        $this->applyConfig();
        $this->publishes([
            $this->getPath('config/table.php') => config_path('table.php')
        ], 'table-config');
    }

    public function register()
    {
        $this->app->extend('datatables.html', function () {
            return $this->app->make(Builder::class);
        });
    }


    protected function getPath($path)
    {
        return realpath(__DIR__ . '/../../' . $path);
    }

    protected function applyConfig()
    {
        $this->mergeConfigFrom($this->getPath('config/table.php'), $this->module);
    }
}
