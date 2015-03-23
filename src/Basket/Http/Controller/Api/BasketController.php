<?php namespace Modules\Basket\Http\Controller\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Basket\Application\Basket\Basket;
use Money\Currency;
use Money\Money;
use PhilipBrown\Basket\Formatters\MoneyFormatter;
use PhilipBrown\Basket\Product;

class BasketController extends Controller
{
    /**
     * @var Basket
     */
    private $basket;
    /**
     * @var MoneyFormatter
     */
    private $moneyFormatter;

    public function __construct(Basket $basket, MoneyFormatter $moneyFormatter)
    {
        $this->basket = $basket;
        $this->moneyFormatter = $moneyFormatter;
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

       // $this->basket->addOrUpdate($sku, $request->get('name'), $price);
        $this->basket->add($sku, $request->get('name'), $price);

        return Response::json($this->getResponseDataForBasket($sku));
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
            return Response::json(array_merge($this->getResponseDataForBasket(), ['removed' => true]));
        }

        $this->basket->update($sku, function (Product $product) use ($count) {
            $product->quantity($count);
        });

        return Response::json($this->getResponseDataForBasket($sku));
    }

    /**
     * Remove a product from the basket
     * @param Request $request
     * @return Response
     */
    public function remove(Request $request)
    {
        $this->basket->remove($request->get('sku'));

        return Response::json($this->getResponseDataForBasket());
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

    /**
     * Return the response data for the current basket
     * @param string $sku
     * @return array
     */
    private function getResponseDataForBasket($sku = null)
    {
        $productInfo = $this->getProductInfoForSku($sku);

        return array_merge([
            'productCount' => $this->basket->products()->count(),
            'itemCount' => $this->basket->meta()->products_count,
            'subtotal' => $this->moneyFormatter->format($this->basket->meta()->subtotal),
            'totalTax' => $this->moneyFormatter->format($this->basket->meta()->tax),
            'totalDelivery' => $this->moneyFormatter->format($this->basket->meta()->delivery),
            'total' => $this->moneyFormatter->format($this->basket->meta()->total),
        ], $productInfo);
    }

    /**
     * Get the product information for the given SKU
     * @param string $sku
     * @return array
     */
    private function getProductInfoForSku($sku)
    {
        $productInfo = [];
        if (! is_null($sku)) {
            $products = $this->basket->products();
            $products = $products->getDictionary();
            $productInfo = [
                'itemTotalTax' => $this->moneyFormatter->format($products[$sku]->total_tax),
                'itemTotal' => $this->moneyFormatter->format($products[$sku]->total),
            ];

            return $productInfo;
        }

        return $productInfo;
    }
}
