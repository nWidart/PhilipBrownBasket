<?php namespace Modules\Basket\Http\Controller;

use Illuminate\Routing\Controller;
use Modules\Basket\Application\Basket\Basket;

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
        $basket = $this->basket->current();

        return view('basket::index', compact('basket'));
    }
}
