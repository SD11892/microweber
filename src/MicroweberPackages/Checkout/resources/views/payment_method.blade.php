@extends('checkout::layout')

@section('content')

    <div class="col-12">
        <form method="post" action="{{ route('checkout.payment_method_save') }}">

            <a href="{{ site_url() }}" class="btn btn-outline-primary"><i class="mdi mdi-arrow-left"></i> {{ _e('Back to shopping') }}</a>
            <a href="{{ route('checkout.cart') }}" class="btn btn-outline-primary"><i class="mdi mdi-cart"></i> {{ _e('Back to cart') }}</a>
            <a href="{{ route('checkout.contact_information') }}" class="btn btn-outline-primary"><i class="mdi mdi-phone"></i> {{ _e('Back to contact information') }}</a>
            <a href="{{ route('checkout.shipping_method') }}" class="btn btn-outline-primary"><i class="mdi mdi-shipping"></i> {{ _e('Back to shipping information') }}</a>
            <div class="shop-cart" style="margin-top:25px;">


                <module type="shop/payments" template="payments-1" />

                <module type="shop/checkout/terms" />

            </div>

            <button type="submit" class="btn btn-info">{{ _e('Continue') }}</button>
        </form>
    </div>

@endsection
