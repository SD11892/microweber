<?php

$config = array();
$config['name'] = "Pickup";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "online shop";
$config['position'] = 900;
$config['type'] = "shipping_gateway";
$config['version'] = "0.2";


$config['settings']['service_provider'] = [
    \MicroweberPackages\Shop\Shipping\Gateways\Pickup\PickupEventServiceProvider::class,
    \MicroweberPackages\Shop\Shipping\Gateways\Pickup\PickupServiceProvider::class,
];