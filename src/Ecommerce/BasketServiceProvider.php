<?php namespace Modules\Ecommerce;

use Illuminate\Support\ServiceProvider;
use Modules\Ecommerce\Application\Basket\PhilipBrownBasket;
use Modules\Ecommerce\Infrastructure\Repositories\SessionBasketRepository;
use PhilipBrown\Basket\Basket;
use PhilipBrown\Basket\Jurisdictions\Belgium;

class BasketServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Modules\Ecommerce\Domain\Repository\BasketRepository', function ($app) {
            return new SessionBasketRepository($app['session']);
        });

        $this->app->bind('Modules\Ecommerce\Application\Basket\Basket', function ($app) {
            $basketRepository = $app['Modules\Ecommerce\Domain\Repository\BasketRepository'];

            $currentBasket = $basketRepository->current();
            $basket = $currentBasket ?: new Basket(new Belgium());

            return new PhilipBrownBasket($basket, $basketRepository);
        });
    }
}
