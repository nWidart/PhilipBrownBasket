<?php namespace Modules\Basket\Http\Controller\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Basket\Application\Basket\Basket;
use Money\Currency;
use Money\Money;

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
}
