<?php

Route::get('/basket', ['as' => 'basket.index', 'uses' => 'Modules\Basket\Http\Controller\BasketController@index']);

Route::post('/api/basket/add', ['as' => 'api.basket.add', 'uses' => 'Modules\Basket\Http\Controller\Api\BasketController@add']);
Route::post('/api/basket/remove', ['as' => 'api.basket.remove', 'uses' => 'Modules\Basket\Http\Controller\Api\BasketController@remove']);
