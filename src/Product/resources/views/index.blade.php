@extends('core::layouts.master')

@section('styles')
    <style>
        .show {
            display: inline !important;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h1 class="page-header">Products</h1>
        </div>
    </div>

    @include('core::partials.notifications')

    <div class="row">
        <?php foreach($products as $product): ?>
        <div class="col-3 col-sm-3 col-lg-3">
            <div class="thumbnail">
                <div class="caption">
                    <h2>{{ str_limit($product->name, 19) }}</h2>
                    <p>{{ $product->price/100 }} EUR</p>
                    <p>
                        <a class="btn btn-sm jsActionButton {{ array_key_exists($product->id, $basket->products()->toArray()) ? 'btn-danger btn-remove' : 'btn-info btn-add' }}" href=""
                                data-sku="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}">
                            <i class="fa fa-cart-plus"></i>
                            {{ array_key_exists($product->id, $basket->products()->toArray()) ? 'Remove from basket' : 'Add to basket' }}
                        </a>
                        <span class="jsLoader hidden"><i class="fa fa-spinner fa-pulse"></i></span>
                        <span class="pull-right">
                            <a class="btn btn-xs tip wishlist-add" href="" title=""
                               data-original-title="Add to Wishlist">
                                <i class="fa fa-star-o fa-lg"></i>
                            </a>
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <?php echo $products->render(); ?>
    </div>
@stop

@section('scripts')
    <script src="{{ Module::asset('basket:js/buyableProduct.js') }}"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            $('.tip').tooltip();
            $('.jsActionButton').each(function (index, value) {
                $(value).buyableProduct({
                    addProductToBasketRoute: '{{ route('api.basket.add') }}',
                    removeProductFromBasketRoute: '{{ route('api.basket.remove') }}',
                    productCounterSelector: '.jsProductCounter',
                    addProductClass: 'btn-add',
                    removeProductClass: 'btn-remove',
                    loaderSelector: '.jsLoader'
                });
            });
        });
    </script>
@stop
