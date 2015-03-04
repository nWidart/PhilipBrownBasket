<?php

Route::get('/products', ['as' => 'products.index', 'uses' => 'Modules\Product\Http\Controller\ProductController@index']);
