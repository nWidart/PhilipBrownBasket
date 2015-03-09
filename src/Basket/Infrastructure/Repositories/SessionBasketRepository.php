<?php namespace Modules\Basket\Infrastructure\Repositories;

use Illuminate\Session\SessionManager;
use Modules\Basket\Domain\Repository\BasketRepository;

class SessionBasketRepository implements BasketRepository
{
    /**
     * @var \Illuminate\Session\Store
     */
    private $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function persistBasket($basket)
    {
        $this->session->put('basket', $basket);
    }

    /**
     * Find the current basket
     * @return \Modules\Basket\Application\Basket\Basket
     */
    public function current()
    {
        return $this->session->get('basket');
    }
}
