<?php


namespace MicroweberPackages\Shop\Shipping\Gateways\Country;



use MicroweberPackages\Shipping\Providers\ShippingDriverInterface;
use shop\shipping\gateways\country\shipping_to_country;

class ShippingToCountry implements ShippingDriverInterface
{

    public function isEnabled()
    {
        return true;
    }

    public function title()
    {
        return 'Shipping to country';
    }


    public function cost()
    {

        return (new shipping_to_country())->get_cost();

        //return 1110;
    }


//    public function cost($params=[])
//    {
//        $rates = [];
//        $rates[] = ['name' => 'Delivery to address', 'cost' => 0];
//        $rates[] = ['name' => 'Delivery to address 123', 'cost' => 100];
//        $rates[] = ['name' => 'Delivery to address 1234', 'cost' => 200];
//        return $rates;
//    }
    public function getData()
    {
        return [];
    }

    public function process()
    {
        return [];
    }

}