@extends('core::layouts.master')

@section('styles')
    <style>
        td {
            position: relative;
        }
        .countLoader {
            position: absolute;
            top: 18px;
            right: 15px;
            display: none;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h1 class="page-header">Basket</h1>
        </div>
    </div>

    @include('core::partials.notifications')
    <div class="row">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <td class="col-md-6">Name</td>
                <td class="col-md-1">Quantity</td>
                <td class="col-md-1">Price</td>
                <td class="col-md-2" colspan="2">Total</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach($basket->products() as $product): ?>
            <tr data-sku="{{ $product->sku }}">
                <td>
                    <div class="col-md-2">
                        <img src="http://placephant.com/80x80" alt="..." class="img-thumbnail">
                    </div>
                    {{ $product->name }}
                </td>
                <td>
                    <span class="countLoader"><i class="fa fa-spinner fa-pulse"></i></span>
                    <input class="form-control jsUpdateProductCount" type="number"
                           name="update[{{ $product->sku }}][quantity]"
                           value="{{ $product->quantity }}">
                </td>

                <td>{{ $product->price->getAmount()/100 }} {{ $product->price->getCurrency() }}</td>
                <td>{{ $product->price->getAmount()/100 }} {{ $product->price->getCurrency() }}</td>
                <td>
                    <a class="btn btn-danger btn-xs jsRemoveProduct" href="">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Products</span>
                </td>
                <td class="jsProductCounter">{{ $basket->products()->count() }}</td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Items</span>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Subtotal</span>
                </td>
                <td>{{ $basketMeta['subtotal']->getAmount() / 100 }} {{ $basketMeta['subtotal']->getCurrency() }}</td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Subtotal (with discounts)</span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Discount (7.5%)</span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">VAT</span>
                </td>
                <td>{{ $basketMeta['tax']->getAmount() / 100 }} {{ $basketMeta['tax']->getCurrency() }}</td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Item Based Shipping</span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Global Tax (12.5%)</span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Global discount (5%)</span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Global Shipping</span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="pull-right">Total</span>
                </td>
                <td>{{ $basketMeta['total']->getAmount() / 100 }} {{ $basketMeta['total']->getCurrency() }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@stop

@section('scripts')
    <script src="{{ Module::asset('basket:js/removableProduct.js') }}"></script>
    <script src="{{ Module::asset('basket:js/updatableProduct.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.jsUpdateProductCount').each(function (index, value) {
                $(value).updatableProduct({
                    updateProductCountRoute: '{{ route('api.basket.update') }}',
                    dataSku: 'sku',
                    productCounterSelector: '.jsProductCounter',
                    countLoaderSelector: '.countLoader'
                });
            });

            $('.jsRemoveProduct').each(function (index, value) {
                $(value).removableProduct({
                    removeProductFromBasketRoute: '{{ route('api.basket.remove') }}',
                    productCounterSelector: '.jsProductCounter',
                    dataSku: 'sku'
                });
            });
        });
    </script>
@stop
