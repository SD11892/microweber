<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/12/2021
 * Time: 11:24 AM
 */

namespace MicroweberPackages\Translation\Locale;

use MicroweberPackages\Translation\Locale\Traits\DetailsByLocaleTrait;
use MicroweberPackages\Translation\Locale\Traits\LanguagesByLocaleTrait;
use MicroweberPackages\Translation\Locale\Traits\RegionByLocaleTrait;

class IntlLocale
{
    use DetailsByLocaleTrait, LanguagesByLocaleTrait, RegionByLocaleTrait;

    public static function getDisplayRegion($locale)
    {
        if (isset(self::$regionsByLocale[$locale])) {
            return self::$regionsByLocale[$locale];
        }

        return false;
    }

    public static function getDisplayLanguage($locale)
    {

        if (isset(self::$languagesByLocale[$locale])) {
            return self::$languagesByLocale[$locale];
        }

        return false;
    }

    public static function getDisplayFlag($locale)
    {
        if (isset(self::$detailsByLocale[$locale])) {
            $details = self::$detailsByLocale[$locale];
            return $details['flag'];
        }

        return false;
    }
}