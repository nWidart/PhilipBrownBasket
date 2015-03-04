<?php namespace Modules\Basket\Http\Controller;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Domain\Repository\BasketRepository;

class BasketController extends Controller
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
        $basket = $this->basket->current();

        return view('basket::index', compact('basket'));
    }
}
