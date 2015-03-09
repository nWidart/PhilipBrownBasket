<?php namespace Modules\Product\Http\Controller;

use Illuminate\Routing\Controller;
use Modules\Basket\Application\Basket\Basket;
use Modules\Product\Domain\Entity\Product;

class ProductController extends Controller
{
    /**
     * @var \Modules\Basket\Application\Basket\Basket
     */
    private $basket;

    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    public function index()
    {
        $products = Product::paginate(15);
        $basket = $this->basket->current();

        return view('products::index', compact('products', 'basket'));
    }
}
