<?php namespace Modules\Ecommerce;

use Illuminate\Support\ServiceProvider;
use Modules\Ecommerce\Infrastructure\Repositories\SessionBasketRepository;
use PhilipBrown\Basket\Basket;
use PhilipBrown\Basket\Jurisdictions\Belgium;

class BasketServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Modules\Ecommerce\Application\Basket\Basket', function () {
            return new Basket(new Belgium());
        });

        $this->app->bind('Modules\Ecommerce\Domain\Repository\BasketRepository', function ($app) {
            return new SessionBasketRepository($app['session']);
        });
    }
}
