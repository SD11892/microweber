<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AbppInstallTest extends DuskTestCase
{


    public function testViewDashboard()
    {
        $this->testInstallation();

        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {


            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });


            $browser->visit($siteUrl . 'admin');
            $browser->pause(5000);

            // Wait for redirect after login
            $browser->waitForText('Dashboard', 30);

            $browser->waitForText('Statistics', 30);
            $browser->waitForText('Live Edit', 30);
            $browser->waitForText('Website', 30);
            $browser->waitForText('Shop', 30);
            $browser->waitForText('Modules', 30);
            $browser->waitForText('Marketplace', 30);
            $browser->waitForText('Settings', 30);
            $browser->waitForText('Users', 30);
            $browser->waitForText('Log out', 30);

            $browser->pause(1500);

        });

    }

}
