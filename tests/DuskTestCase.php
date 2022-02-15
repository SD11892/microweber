<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use MicroweberPackages\User\Models\User;
use Tests\Browser\Components\ChekForJavascriptErrors;

abstract class DuskTestCase extends BaseTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    use CreatesApplication;


    protected function setUp(): void
    {
        putenv('APP_ENV=testing');
        $_ENV['APP_ENV'] = 'testing';
        parent::setUp();
    }
    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {

        $options = (new ChromeOptions)->addArguments(collect([
            '--disable-web-security',
            '--disable-xss-auditor',
            '--enable-devtools-experiments',
            '--window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {

            $arguments = [];
            $arguments[] = '--disable-web-security';
            $arguments[] = '--disable-xss-auditor';
            $arguments[] = '--enable-devtools-experiments';
            $arguments[] = '--disable-gpu';
            $arguments[] = '--no-sandbox';
            $arguments[] = '--ignore-certificate-errors';

            if (getenv('GITHUB_RUN_NUMBER')) {
                $arguments[] = '--headless';
            }
            return $items->merge($arguments);

        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            ), 90000, 90000
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    protected function assertPreConditions(): void
    {
        if (mw_is_installed()) {

            save_option('dusk_test', 1, 'dusk');

            \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);
            \DB::table('options')
                ->where('option_group', 'multilanguage_settings')
                ->delete();
        }

    }



}
