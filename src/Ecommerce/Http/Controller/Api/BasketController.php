<?php namespace Modules\Ecommerce\Http\Controller\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Modules\Ecommerce\Application\Basket\Basket;
use Modules\Ecommerce\Domain\Repository\BasketRepository;
use Money\Currency;
use Money\Money;
use PhilipBrown\Basket\Product;

class BasketController extends Controller
{
    /**
     * @var Basket
     */
    private $basket;
    /**
     * @var BasketRepository
     */
    private $basketPersistance;

    public function __construct(BasketRepository $basketPersistance)
    {
        $newBasket = app('Modules\Ecommerce\Application\Basket\Basket');
        $this->basketPersistance = $basketPersistance;
        $this->basket = $this->basketPersistance->current() ?: $newBasket;
    }

    /**
     * Add a product to the basket
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $price = new Money((int) $request->get('price'), new Currency('EUR'));
        $sku = $request->get('sku');

        if ($this->productIsInBasket($sku)) {
            $this->basket->update($sku, function(Product $product) {
                $product->increment();
            });
        } else {
            $this->basket->add($sku, $request->get('name'), $price);
        }

        $this->basketPersistance->persistBasket($this->basket);

        return Response::json([
            'productCount' => $this->basket->count(),
            'products' => $this->basket->products()->toArray()
        ]);
    }

    /**
     * Remove a product from the basket
     * @param Request $request
     * @return Response
     */
    public function remove(Request $request)
    {
        $this->basket->remove($request->get('sku'));

        return Response::json([
            'productCount' => $this->basket->count(),
            'products' => $this->basket->products()->toArray()
        ]);
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
}
