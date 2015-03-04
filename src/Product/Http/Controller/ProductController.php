<?php namespace Modules\Product\Http\Controller;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Domain\Repository\BasketRepository;
use Modules\Product\Domain\Entity\Product;

class ProductController extends Controller
{
    /**
     * @var BasketRepository
     */
    private $basket;

    public function __construct(BasketRepository $basket)
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
