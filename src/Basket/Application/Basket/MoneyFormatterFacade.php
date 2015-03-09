<?php namespace Modules\Basket\Application\Basket;

use Illuminate\Support\Facades\Facade;

class MoneyFormatterFacade extends Facade
{
    public static function getFacadeAccessor() { return 'PhilipBrown\Basket\Formatters\MoneyFormatter'; }
}
