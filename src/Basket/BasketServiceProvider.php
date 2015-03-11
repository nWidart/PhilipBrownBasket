<?php namespace Modules\Basket;

use Cartalyst\Cart\Cart;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Basket\Application\Basket\CartalystCartBasket;
use Modules\Basket\Application\Basket\PhilipBrownBasket;
use Modules\Basket\Infrastructure\Repositories\SessionBasketRepository;
use PhilipBrown\Basket\Basket;
use PhilipBrown\Basket\Formatters\MoneyFormatter;
use PhilipBrown\Basket\Jurisdictions\Belgium;

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
        $this->app['view']->addNamespace('basket', __DIR__ . '/resources/views');
    }

    private function registerBindings()
    {
        $this->app->bind('Modules\Basket\Domain\Repository\BasketRepository', function ($app) {
            return new SessionBasketRepository($app['session']);
        });

        $this->bindPhilipBrownBasket();
        //$this->bindCartalystCartBasket();

        $this->setupMoneyFormatter();
    }

    public function provides()
    {
        return ['PhilipBrown\Basket\Formatters\MoneyFormatter'];
    }

    /**
     * Setup the money formatter and register the Laravel Facade
     */
    private function setupMoneyFormatter()
    {
        $this->app->bindShared('PhilipBrown\Basket\Formatters\MoneyFormatter', function () {
            return new MoneyFormatter('nl_NL');
        });
        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias('MoneyFormatter', 'Modules\Basket\Application\Basket\MoneyFormatterFacade');
    }

    private function bindPhilipBrownBasket()
    {
        $this->app->bind('Modules\Basket\Application\Basket\Basket', function ($app) {
            $basketRepository = $app['Modules\Basket\Domain\Repository\BasketRepository'];

            $currentBasket = $basketRepository->current();
            $basket = $currentBasket ?: new Basket(new Belgium());

            return new PhilipBrownBasket($basket, $basketRepository);
        });
    }

    private function bindCartalystCartBasket()
    {
        $this->app->bind('Modules\Basket\Application\Basket\Basket', function ($app) {
            return new CartalystCartBasket($app['cart']);
        });
    }
}
