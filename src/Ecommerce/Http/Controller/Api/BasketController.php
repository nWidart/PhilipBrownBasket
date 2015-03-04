<?php namespace Modules\Ecommerce\Http\Controller\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Ecommerce\Application\Basket\Basket;
use Money\Currency;
use Money\Money;
use PhilipBrown\Basket\Product;

class BasketController extends Controller
{
    /**
     * @var Basket
     */
    private $basket;

    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
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
