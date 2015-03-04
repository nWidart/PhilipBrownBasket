<?php namespace Modules\Basket;

use Illuminate\Support\ServiceProvider;

class BasketServiceProvider extends ServiceProvider
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
        $this->app['view']->addNamespace('basket', __DIR__.'/resources/views');
    }
}
