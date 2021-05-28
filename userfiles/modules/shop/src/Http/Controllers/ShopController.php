<?php

namespace MicroweberPackages\Shop\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Product\Models\Product;

class ShopController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $moduleId = $request->get('id');

        $postQuery = Product::query();

        $postResults = $postQuery->frontendFilter([
            'moduleId'=>$moduleId
        ]);

        return view('shop::index', ['posts'=>$postResults,'moduleId'=>$moduleId]);
    }

}
