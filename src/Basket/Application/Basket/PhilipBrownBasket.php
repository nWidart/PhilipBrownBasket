<?php namespace Modules\Basket\Application\Basket;

use Closure;
use Modules\Basket\Application\Basket\MetaData\BasketDiscountMetaData;
use Modules\Basket\Domain\Repository\BasketRepository;
use Money\Currency;
use Money\Money;
use PhilipBrown\Basket\Basket as PhilipBrownBasketImplementation;
use PhilipBrown\Basket\Collection;
use PhilipBrown\Basket\MetaData\DeliveryMetaData;
use PhilipBrown\Basket\MetaData\DiscountMetaData;
use PhilipBrown\Basket\MetaData\ProductsMetaData;
use PhilipBrown\Basket\MetaData\SubtotalMetaData;
use PhilipBrown\Basket\MetaData\TaxableMetaData;
use PhilipBrown\Basket\MetaData\TaxMetaData;
use PhilipBrown\Basket\MetaData\TotalMetaData;
use PhilipBrown\Basket\MetaData\ValueMetaData;
use PhilipBrown\Basket\Processor;
use PhilipBrown\Basket\Product;
use PhilipBrown\Basket\Reconcilers\DefaultReconciler;
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
        $processor = $this->reconcile();

        $products = array_map(function ($product) {
            return (object) $product;
        }, $processor->products($this->current()));

        return new Collection($products);
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
     * Add or update a product in the basket
     * @param string $sku
     * @param string $name
     * @param Money $price
     * @return void
     */
    public function addOrUpdate($sku, $name, Money $price)
    {
        if ($this->productIsInBasket($sku)) {
            $this->update($sku, function (Product $product) {
                $product->increment();
            });
        } else {
            $this->add($sku, $name, $price);
        }
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

    /**
     * Find the current basket
     * @return \Modules\Basket\Application\Basket\Basket
     */
    public function current()
    {
        return $this->basketRepository->current() ?: $this->basket;
    }

    /**
     * Get the processed meta data of the basket
     * @return object
     */
    public function meta()
    {
        $processor = $this->reconcile();

        return (object) $processor->meta($this->current());
    }

    /**
     * Check if the given product sku number is present in the basket
     * @param string $sku
     * @return bool
     */
    private function productIsInBasket($sku)
    {
        return array_key_exists($sku, $this->basket->products()->toArray());
    }

    /**
     * @return Processor
     */
    private function reconcile()
    {
        $reconciler = new DefaultReconciler;

        $meta = [
            new DeliveryMetaData($reconciler),
            new DiscountMetaData($reconciler),
            new ProductsMetaData,
            new SubtotalMetaData($reconciler),
            new TaxableMetaData,
            new TaxMetaData($reconciler),
            new TotalMetaData($reconciler),
            new ValueMetaData($reconciler),
        ];

        return new Processor($reconciler, $meta);
    }

    /**
     * Add a global discount on the basket
     * @param Money $money
     */
    public function addDiscount(Money $money)
    {
        $this->basket->addDiscount($money);
    }
}
