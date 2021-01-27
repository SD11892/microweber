<?php


namespace MicroweberPackages\Shipping\Providers;



class ShippingToCountry implements ShippingProviderInterface
{


    public function title()
    {
        return 'Shipping to country';
    }


    public function cost()
    {
        return 0;
    }


//    public function cost($params=[])
//    {
//        $rates = [];
//        $rates[] = ['name' => 'Delivery to address', 'cost' => 0];
//        $rates[] = ['name' => 'Delivery to address 123', 'cost' => 100];
//        $rates[] = ['name' => 'Delivery to address 1234', 'cost' => 200];
//        return $rates;
//    }


    public function process()
    {
        return true;
    }

}