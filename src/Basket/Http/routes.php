<?php

Route::get('/basket', ['as' => 'basket.index', 'uses' => 'Modules\Basket\Http\Controller\BasketController@index']);
