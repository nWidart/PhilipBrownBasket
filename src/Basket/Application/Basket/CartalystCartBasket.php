<?php namespace Modules\Basket\Application\Basket;

use Cartalyst\Cart\Cart;
use Closure;
use Money\Currency;
use Money\Money;
use PhilipBrown\Basket\Collection;
use PhilipBrown\Basket\Product;
use PhilipBrown\Basket\TaxRate;

class CartalystCartBasket implements Basket
{
    /**
     * @var Cart
     */
    private $basket;

    public function __construct(Cart $basket)
    {
        $this->basket = $basket;
    }

    /**
     * Get the TaxRate of the Basket
     * @return TaxRate
     */
    public function rate()
    {
        // TODO: Implement rate() method.
    }

    /**
     * Get the Currency of the Basket
     * @return Currency
     */
    public function currency()
    {
        // TODO: Implement currency() method.
    }

    /**
     * Get the products from the basket
     * @return Collection
     */
    public function products()
    {
        // TODO: Implement products() method.
    }

    /**
     * Count the items in the basket
     * @return int
     */
    public function count()
    {
        // TODO: Implement count() method.
    }

    /**
     * Pick a product from the basket
     * @param string $sku
     * @return Product
     */
    public function pick($sku)
    {
        // TODO: Implement pick() method.
    }

    /**
     * Add a product to the basket
     * @param string $sku
     * @param string $name
     * @param Money $price
     * @param Closure $action
     * @return void
     */
    public function add($sku, $name, Money $price, Closure $action = null)
    {
        // TODO: Implement add() method.
    }

    /**
     * Update a product that is already in the basket
     * @param string $sku
     * @param Closure $action
     * @return void
     */
    public function update($sku, Closure $action)
    {
        // TODO: Implement update() method.
    }

    /**
     * Add or update a product in the basket
     * @param string $sku
     * @param string $name
     * @param Money $price
     * @return void
     */
    public function addOrUpdate($sku, $name, Money $price)
    {
        // TODO: Implement addOrUpdate() method.
    }

    /**
     * Remove a product from the basket
     * @param string $sku
     * @return void
     */
    public function remove($sku)
    {
        // TODO: Implement remove() method.
    }

    /**
     * Find the current basket
     * @return \Modules\Basket\Application\Basket\Basket
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * Get the processed meta data of the basket
     * @return array
     */
    public function meta()
    {
        // TODO: Implement meta() method.
    }
}
