<?php namespace Modules\Ecommerce\Domain\Repository;

interface BasketRepository
{
    /**
     * @param \Modules\Ecommerce\Application\Basket\Basket $basket
     * @return void
     */
    public function persistBasket($basket);

    /**
     * Find the current basket
     * @return \Modules\Ecommerce\Application\Basket\Basket
     */
    public function current();
}
