<?php


namespace DuoLee\Table\Providers;


use Config;
use DuoLee\Table\Supports\Builder;
use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    protected $module = 'table';

    public function boot()
    {
        $this->loadTranslationsFrom($this->getPath('resources/lang'), $this->module);
        $this->loadViewsFrom($this->getPath('resources/views'), $this->module);
        $this->mergeConfigFrom($this->getPath('config/table-exts.php'), $this->module);
        Config::set('table-exts', Config::get($this->module));
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
}
