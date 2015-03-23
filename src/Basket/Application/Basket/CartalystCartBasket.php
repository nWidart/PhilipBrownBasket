<?php namespace Modules\Basket\Application\Basket;

use Cartalyst\Cart\Cart;
use Cartalyst\Conditions\Condition;
use Closure;
use Money\Currency;
use Money\Money;
use PhilipBrown\Basket\Collection;
use Modules\Product\Domain\Entity\Product;
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
        // Get the product from the database
        if ( ! $product = Product::find($sku)) {
            return redirect('/');
        }
        $this->addToCart($product);
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
        return $this->basket->items();
    }

    /**
     * Get the processed meta data of the basket
     * @return array
     */
    public function meta()
    {
        // TODO: Implement meta() method.
    }

    /**
     * Add product to cart.
     * @param Product $product
     * @return \Cartalyst\Cart\Collections\ItemCollection
     */
    protected function addToCart(Product $product)
    {
        // Set the global conditions order
        $this->basket->setConditionsOrder([
            'discount',
            'other',
            'tax',
            'shipping',
            'coupon',
        ]);
        // Set the items conditions order
        $this->basket->setItemsConditionsOrder([
            'discount',
            'other',
            'tax',
            'shipping',
        ]);
        // Item conditions
        $condition1 = $this->createCondition('VAT (17.5%)', 'tax', 'subtotal', ['value' => '17.50%']);
        $condition2 = $this->createCondition('VAT (23%)', 'tax', 'subtotal', ['value' => '23%']);
        $condition3 = $this->createCondition('Discount (7.5%)', 'discount', 'subtotal', ['value' => '-7.5%']);
        $condition4 = $this->createCondition('Item Based Shipping', 'shipping', 'subtotal', ['value' => '20.00']);
        // Add the item to the basket
        $item = $this->basket->add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'conditions' => [$condition1, $condition2, $condition3, $condition4],
        ]);
        // Global conditions
        $condition1 = $this->createCondition('Global Tax (12.5%)', 'tax', 'subtotal', ['value' => '12.50%']);
        $condition2 = $this->createCondition('Global discount (5%)', 'tax', 'subtotal', ['value' => '-5%']);
        $condition3 = $this->createCondition('Global Shipping', 'shipping', 'subtotal', ['value' => '20.00%']);
        // Set the global conditions
        $this->basket->condition([$condition1, $condition2, $condition3]);

        return $item;
    }

    /**
     * Create a new condition.
     * @param  string $name
     * @param  string $type
     * @param  string $target
     * @param  array $actions
     * @param  array $rules
     * @return \Cartalyst\Conditions\Condition
     */
    protected function createCondition($name, $type, $target, $actions = [], $rules = [])
    {
        $condition = new Condition(compact('name', 'type', 'target'));
        $condition->setActions($actions);
        $condition->setRules($rules);

        return $condition;
    }
}
