<?php namespace Modules\Basket\Application\Basket;

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
     * Add or update a product in the basket
     * @param string $sku
     * @param string $name
     * @param Money $price
     * @return void
     */
    public function addOrUpdate($sku, $name, Money $price);

    /**
     * Remove a product from the basket
     *
     * @param string $sku
     * @return void
     */
    public function remove($sku);

    /**
     * Find the current basket
     * @return \Modules\Basket\Application\Basket\Basket
     */
    public function current();

    /**
     * Get the processed meta data of the basket
     * @return array
     */
    public function meta();

    /**
     * Add a global discount on the basket
     * @param Money $money
     */
    public function addDiscount(Money $money);
}
