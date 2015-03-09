<?php namespace Modules\Basket;

use Illuminate\Support\ServiceProvider;
use Modules\Basket\Application\Basket\PhilipBrownBasket;
use Modules\Basket\Infrastructure\Repositories\SessionBasketRepository;

class BasketServiceProvider extends ServiceProvider
{
    public function register()
    {
        include __DIR__ . '/Http/routes.php';
        $this->registerBindings();
    }

    public function boot()
    {
        $this->registerResourceNamespaces();
    }

    private function registerResourceNamespaces()
    {
        $this->app['view']->addNamespace('basket', __DIR__.'/resources/views');
    }

    private function registerBindings()
    {
        $this->app->bind('Modules\Basket\Domain\Repository\BasketRepository', function ($app) {
            return new SessionBasketRepository($app['session']);
        });

        $this->app->bind('Modules\Basket\Application\Basket\Basket', function ($app) {
            $basketRepository = $app['Modules\Basket\Domain\Repository\BasketRepository'];

            $currentBasket = $basketRepository->current();
            $basket = $currentBasket ?: new Basket(new Belgium());

            return new PhilipBrownBasket($basket, $basketRepository);
        });
    }
}
