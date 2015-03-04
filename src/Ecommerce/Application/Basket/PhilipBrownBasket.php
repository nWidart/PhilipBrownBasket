<?php namespace Modules\Ecommerce\Application\Basket;

use Closure;
use Modules\Ecommerce\Domain\Repository\BasketRepository;
use Money\Currency;
use Money\Money;
use PhilipBrown\Basket\Basket as PhilipBrownBasketImplementation;
use PhilipBrown\Basket\Collection;
use PhilipBrown\Basket\Product;
use PhilipBrown\Basket\TaxRate;

class PhilipBrownBasket implements Basket
{
    /**
     * @var
     */
    private $basket;
    /**
     * @var BasketRepository
     */
    private $basketRepository;

    public function __construct(PhilipBrownBasketImplementation $basket, BasketRepository $basketRepository)
    {
        $this->basket = $basket;
        $this->basketRepository = $basketRepository;
    }

    /**
     * Get the TaxRate of the Basket
     * @return TaxRate
     */
    public function rate()
    {
        return $this->basket->rate();
    }

    /**
     * Get the Currency of the Basket
     * @return Currency
     */
    public function currency()
    {
        return $this->basket->currency();
    }

    /**
     * Get the products from the basket
     * @return Collection
     */
    public function products()
    {
        return $this->basket->products();
    }

    /**
     * Count the items in the basket
     * @return int
     */
    public function count()
    {
        return $this->basket->count();
    }

    /**
     * Pick a product from the basket
     * @param string $sku
     * @return Product
     */
    public function pick($sku)
    {
        return $this->basket->pick($sku);
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
        $this->basket->add($sku, $name, $price, $action);

        $this->basketRepository->persistBasket($this->basket);
    }

    /**
     * Update a product that is already in the basket
     * @param string $sku
     * @param Closure $action
     * @return void
     */
    public function update($sku, Closure $action)
    {
        $this->basket->update($sku, $action);

        $this->basketRepository->persistBasket($this->basket);
    }

    /**
     * Remove a product from the basket
     * @param string $sku
     * @return void
     */
    public function remove($sku)
    {
        $this->basket->remove($sku);

        $this->basketRepository->persistBasket($this->basket);
    }
}
