<?php

Route::post('/api/basket/add', ['as' => 'api.basket.add', 'uses' => 'Modules\Ecommerce\Http\Controller\Api\BasketController@add']);
Route::post('/api/basket/remove', ['as' => 'api.basket.remove', 'uses' => 'Modules\Ecommerce\Http\Controller\Api\BasketController@remove']);
