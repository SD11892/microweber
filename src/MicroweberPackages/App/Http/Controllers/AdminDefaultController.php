<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/2/2020
 * Time: 2:20 PM
 */

namespace MicroweberPackages\App\Http\Controllers;

use Illuminate\Routing\Controller;

class AdminDefaultController extends Controller {

    public $middleware = [
        [
            'middleware'=>'admin',
            'options'=>[]
        ],
        [
            'middleware'=>'xss',
            'options'=>[]
        ]
    ];

}