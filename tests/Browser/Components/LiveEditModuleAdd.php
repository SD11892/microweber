<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use PHPUnit\Framework\Assert as PHPUnit;

class LiveEditModuleAdd extends BaseComponent
{
    public static $increment = 0;
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {

    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }

    public function addModule(Browser $browser, $name)
    {
        $browser->pause(2000);

        if(!$browser->driver->findElement(WebDriverBy::cssSelector('#mw-sidebar-modules-list'))->isDisplayed()) {
            $browser->script("$('.mw-lsmodules-tab').trigger('mousedown').trigger('mouseup').click()");
            $browser->pause(500);
        }

       // $randClassSeachInput = 'js-rand-live-edit-test-search-' . time() . rand(1111, 9999).self::$increment++;

        $browser->script("$('#mw-sidebar-search-input-for-modules').val('')");
        $browser->pause(1500);
      //  $browser->pause(1000);
       // $browser->keys('.' . $randClassSeachInput, $name);
        $browser->keys('#mw-sidebar-search-input-for-modules', $name);


        $browser->pause(3000);

        $randClassSearchedModule = 'js-rand-live-edit-test-' .str_slug($name). time() . rand(1111, 9999).self::$increment++;
        $browser->script("$('#mw-sidebar-modules-list').find('li.module-item:visible').addClass('$randClassSearchedModule')");


        $browser->click('.'.$randClassSearchedModule);
        $browser->pause(4000);

    }
}
