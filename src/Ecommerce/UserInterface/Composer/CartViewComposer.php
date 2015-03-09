<?php namespace Modules\Ecommerce\UserInterface\Composer;

use Illuminate\Contracts\View\View;
use Modules\Ecommerce\Application\Basket\Basket;
use Modules\Ecommerce\Domain\Repository\BasketRepository;

class CartViewComposer
{
    /**
     * @var Basket
     */
    private $basket;

    public function __construct(BasketRepository $basket)
    {
        $newBasket = app('Modules\Ecommerce\Application\Basket\Basket');
        $this->basket = $basket->current() ?: $newBasket;
    }

    public function compose(View $view)
    {
        $view->with('basket', $this->basket);
    }
}
