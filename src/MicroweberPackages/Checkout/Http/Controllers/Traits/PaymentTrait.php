<?php
namespace MicroweberPackages\Checkout\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait PaymentTrait {

    public function paymentMethod() {

        // Validate Contact Information
        $validateContactInformation = $this->_validateContactInformation();;
        if ($validateContactInformation['valid'] == false) {
            session_set('errors', $validateContactInformation['errors']);
            return redirect(route('checkout.contact_information'));
        }

        // Validate Shipping Method
        $validateShippingMethod = $this->_validateShippingMethod();;
        if ($validateShippingMethod['valid'] == false) {
            session_set('errors', $validateShippingMethod['errors']);
            return redirect(route('checkout.shipping_method'));
        }

        $data = [];
        $data['errors'] = session_get('errors');
        $data['checkout_session'] = session_get('checkout_v2');

        session_del('errors');

        return $this->_renderView('checkout::payment_method', $data);
    }

    public function paymentMethodSave(Request $request) {

        session_append_array('checkout_v2', [
            'payment_gw'=> $request->get('payment_gw'),
            'terms'=> $request->get('terms'),
        ]);

        $checkoutData = session_get('checkout_v2');

        // Add new session to old session
        $checkoutData = session_set('checkout', $checkoutData);

        try {
            $sendCheckout = app()->checkout_manager->checkout($checkoutData);
        } catch (\Exception $e) {
            session_set('errors', [
                'payment_errors'=>['error'=>$e->getMessage()]
            ]);
            return redirect(route('checkout.payment_method'));
        }

        if (is_object($sendCheckout)) {
            $sendCheckout = $sendCheckout->getOriginalContent();
            if (isset($sendCheckout['errors'])) {
                session_set('errors', $sendCheckout['errors']);
                return redirect(route('checkout.payment_method'));
            }
        }

        // Payment error
        if (isset($sendCheckout['error'])) {
            session_set('errors', [
                'payment_errors'=>['error'=>$sendCheckout['error']]
            ]);
            return redirect(route('checkout.payment_method'));
        }

        // Cart is empty
        if (isset($sendCheckout['error']['cart_empty'])) {
            session_set('errors', [
                'payment_errors'=>['error'=>$sendCheckout['error']['cart_empty']]
            ]);
            return redirect(route('checkout.cart'));
        }

        return redirect(route('checkout.finish', $sendCheckout['id']));
    }
}
