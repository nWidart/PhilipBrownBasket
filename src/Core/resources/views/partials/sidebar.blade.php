<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <li class="{{ Request::is('/') ? 'active' : '' }}">
            <a href="">
                <i class="fa fa-tachometer"></i> Overview
            </a>
        </li>
        <li class="{{ Request::is('products*') ? 'active' : '' }}">
            <a href="{{ route('products.index') }}"><i class="fa fa-cubes"></i> Products</a>
        </li>
        <li class="{{ Request::is('basket*') ? 'active' : '' }}">
            <a href="{{ route('basket.index') }}">
                <i class="fa fa-shopping-cart"></i> Basket
                <span class="badge jsProductCounter" style="float:right;">{{ $basket->count() }}</span>
            </a>
        </li>
    </ul>
</div>
