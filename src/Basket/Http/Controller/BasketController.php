<?php namespace Modules\Basket\Http\Controller;

use Illuminate\Routing\Controller;
use Modules\Basket\Application\Basket\Basket;
use PhilipBrown\Basket\Formatters\MoneyFormatter;
use Modules\Basket\Application\Basket\MoneyFormatterFacade;

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

    public function index()
    {
        $basket = $this->basket->meta();
        $products = $this->basket->products();

        return view('basket::index', compact('basket', 'products'));
    }
}
