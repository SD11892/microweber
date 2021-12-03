<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class LiveEditTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testLiveEditSearchinSidebarForModules()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->visit($siteUrl . '/?editmode=y');
            $browser->pause(4000);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


            if(!$browser->driver->findElement(WebDriverBy::cssSelector('#mw-sidebar-modules-list'))->isDisplayed()) {
                $browser->script("$('.mw-lsmodules-tab').trigger('mousedown').trigger('mouseup').click()");
                $browser->pause(500);
            }

            $randClassFoundBeForeSearch = 'js-rand-liveeditrtest-randClassFoundBeForeSearch-' . time() . rand(1111, 9999);
            $browser->script("$('#mw-sidebar-modules-list').find('li.module-item:visible').addClass('$randClassFoundBeForeSearch')");


            $randClass = 'js-rand-liveeditrtest-' . time() . rand(1111, 9999);
            $browser->script("$('#mw-sidebar-search-input-for-modules').addClass('$randClass')");


            $browser->keys('.' . $randClass, 'Contact form');

            $randClassFound = 'js-rand-liveeditrtest-results-' . time() . rand(1111, 9999);
            $browser->script("$('#mw-sidebar-modules-list').find('li.module-item:visible').addClass('$randClassFound')");


            $browser->pause(1000);

            $this->assertEquals(1, count($browser->elements('.' . $randClassFound)));
            for ($i = 0; $i <= 20; $i++) {
                $browser->keys('.' . $randClass, '{backspace}');
            }


            $randClassFound2 = 'js-rand-liveeditrtest-results-' . time() . rand(1111, 9999);
            $browser->script("$('#mw-sidebar-modules-list').find('li.module-item:visible').addClass('$randClassFound2')");
            $browser->pause(1000);

            $arr1 = $browser->elements('.' . $randClassFoundBeForeSearch);
            $arr2 = $browser->elements('.' . $randClassFound2);

            $this->assertEquals(count($arr2), count($arr1));

        });
    }

    public function testLiveEditSearchinSidebarForLayouts()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->visit($siteUrl . '/?editmode=y');
            $browser->pause(4000);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


            if(!$browser->driver->findElement(WebDriverBy::cssSelector('#mw-sidebar-layouts-list'))->isDisplayed()) {
                $browser->script("$('.mw-lslayout-tab').trigger('mousedown').trigger('mouseup').click()");
                $browser->pause(500);
            }

            $randClassFoundBeForeSearch = 'js-rand-liveeditrlayputtest-randClassFoundBeForeSearch-' . time() . rand(1111, 9999);
            $browser->script("$('#mw-sidebar-layouts-list').find('li.module-item:visible').addClass('$randClassFoundBeForeSearch')");

            $browser->pause(500);
            $randClass = 'js-rand-livelaypouteditrtest-' . time() . rand(1111, 9999);
            $browser->script("$('#mw-sidebar-search-input-for-modules-and-layouts').addClass('$randClass')");

            $browser->pause(500);
            $browser->keys('.' . $randClass, 'Testimonial');

            $browser->pause(500);
            $randClassFound = 'js-rand-liveeditrtest-results-' . time() . rand(1111, 9999);
            $browser->script("$('#mw-sidebar-layouts-list').find('li.module-item-layout:visible').addClass('$randClassFound')");


            $this->assertEquals(3, count($browser->elements('.' . $randClassFound)));
            for ($i = 0; $i <= 20; $i++) {
                $browser->keys('.' . $randClass, '{backspace}');
            }


            $browser->pause(1000);
            $randClassFound2 = 'js-rand-liveeditrtest222-results-' . time() . rand(1111, 9999);
            $browser->script("$('#mw-sidebar-layouts-list').find('li.module-item-layout:visible').addClass('$randClassFound2')");
            $browser->pause(1000);

            $arr1 = $browser->elements('.' . $randClassFoundBeForeSearch);
            $arr2 = $browser->elements('.' . $randClassFound2);

            $this->assertNotEmpty($arr1);
            $this->assertNotEmpty($arr2);

        });
    }
}
