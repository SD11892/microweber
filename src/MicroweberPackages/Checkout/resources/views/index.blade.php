@extends('checkout::layout')

@section('content')
<div class="col-12">
    <a href="{{ site_url() }}" class="btn btn-outline-primary"><i class="mdi mdi-arrow-left"></i> {{ _e('Back to shopping') }}</a>
    <div class="shop-cart" style="margin-top:25px;">
        <module type="shop/cart" template="mw_default" data-checkout-link-enabled="n" />
    </div>
</div>

<div class="col-12">
    <a href="{{ route('checkout.contact_information') }}" class="btn btn-primary">{{ _e('Continue') }}</a>
</div>
@endsection
