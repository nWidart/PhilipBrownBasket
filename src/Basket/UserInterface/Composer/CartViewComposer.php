<?php namespace Modules\Basket\UserInterface\Composer;

use Illuminate\Contracts\View\View;
use Modules\Basket\Application\Basket\Basket;

class CartViewComposer
{
    /**
     * @var Basket
     */
    private $basket;

    public function __construct(Basket $basket)
    {
        $this->basket = $basket->current();
    }

    public function compose(View $view)
    {
        $view->with('basket', $this->basket);
    }
}
