<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/13/2020
 * Time: 12:16 PM
 */

namespace MicroweberPackages\Form\Http\Controllers\ApiPublic;

use Illuminate\Http\Request;

class FormController
{

    public function post(Request $request)
    {

        var_dump($_FILES);
        var_dump($_POST);
        return;


        return mw()->forms_manager->post($request->all());

    }
}