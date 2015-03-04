<?php namespace Modules\Basket\Http\Controller;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Application\Basket\Basket;
use Modules\Ecommerce\Domain\Repository\BasketRepository;

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
