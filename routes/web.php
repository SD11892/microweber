<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('test123', function(){

    $availableTranslations = \MicroweberPackages\Translation\TranslationPackageInstallHelper::getAvailableTranslations();

    foreach($availableTranslations as $availableLocale=>$availableLanguage) {
        $installResponse = \MicroweberPackages\Translation\TranslationPackageInstallHelper::installLanguage($availableLocale);
    }

    \MicroweberPackages\Translation\TranslationPackageInstallHelper::installLanguage('bg_BG');

});*/

// Route::get('favorite-drink', '\App\Http\Controllers\Controller@favoriteDrink');