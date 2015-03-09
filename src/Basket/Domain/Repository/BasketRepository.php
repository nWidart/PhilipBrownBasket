<?php namespace Modules\Basket\Domain\Repository;

interface BasketRepository
{
    /**
     * @param \Modules\Basket\Application\Basket\Basket $basket
     * @return void
     */
    public function persistBasket($basket);

    /**
     * Find the current basket
     * @return \Modules\Basket\Application\Basket\Basket
     */
    public function current();
}
