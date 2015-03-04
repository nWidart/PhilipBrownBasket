<?php namespace Modules\Ecommerce\Application\Basket;

use Closure;
use Money\Currency;
use Money\Money;
use PhilipBrown\Basket\Collection;
use PhilipBrown\Basket\Product;
use PhilipBrown\Basket\TaxRate;

interface Basket
{
    /**
     * Get the TaxRate of the Basket
     *
     * @return TaxRate
     */
    public function rate();

    /**
     * Get the Currency of the Basket
     *
     * @return Currency
     */
    public function currency();

    /**
     * Get the products from the basket
     *
     * @return Collection
     */
    public function products();

    /**
     * Count the items in the basket
     *
     * @return int
     */
    public function count();

    /**
     * Pick a product from the basket
     *
     * @param string $sku
     * @return Product
     */
    public function pick($sku);

    /**
     * Add a product to the basket
     *
     * @param string $sku
     * @param string $name
     * @param Money $price
     * @param Closure $action
     * @return void
     */
    public function add($sku, $name, Money $price, Closure $action = null);

    /**
     * Update a product that is already in the basket
     *
     * @param string $sku
     * @param Closure $action
     * @return void
     */
    public function update($sku, Closure $action);

    /**
     * Remove a product from the basket
     *
     * @param string $sku
     * @return void
     */
    public function remove($sku);
}
