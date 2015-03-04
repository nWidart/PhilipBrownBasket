<?php namespace Modules\Product;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function register()
    {
        include __DIR__ . '/Http/routes.php';
    }

    public function boot()
    {
        $this->registerResourceNamespaces();
    }

    private function registerResourceNamespaces()
    {
        $this->app['view']->addNamespace('products', __DIR__.'/resources/views');
    }
}
