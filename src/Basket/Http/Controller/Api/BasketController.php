<?php namespace Modules\Basket\Http\Controller\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Basket\Application\Basket\Basket;
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

        $this->basket->addOrUpdate($sku, $request->get('name'), $price);

        return Response::json([
            'productCount' => $this->basket->count(),
            'products' => $this->basket->products()->toArray()
        ]);
    }

    /**
     * Update a product on the Basket
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $count = (int) $request->get('count');
        $sku = $request->get('sku');

        if ($this->guardForNegativeCount($count, $sku)) {
            return Response::json([
                'removed' => true,
                'productCount' => $this->basket->products()->count(),
                'itemCount' => $this->basket->meta()->products_count
            ]);
        }

        $this->basket->update($sku, function (Product $product) use ($count) {
            $product->quantity($count);
        });

        return Response::json([
            'productCount' => $this->basket->products()->count(),
            'itemCount' => $this->basket->meta()->products_count
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
            'productCount' => $this->basket->products()->count(),
            'itemCount' => $this->basket->meta()->products_count
        ]);
    }

    /**
     * Guard for negative product count
     * @param int $count
     * @param string $sku
     * @return bool
     */
    private function guardForNegativeCount($count, $sku)
    {
        if ($count <= 0) {
            $request = new Request(compact('sku'));
            $this->remove($request);

            return true;
        }

        return false;
    }
}
