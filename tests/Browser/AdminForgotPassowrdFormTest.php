<?php

namespace Tests\Browser;

use Carbon\Carbon;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\PasswordReset;
use MicroweberPackages\User\Models\User;
use Tests\DuskTestCase;

class AdminForgotPassowrdFormTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmitEmail()
    {

        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $user = User::where('username', 1)->first();
            $user->email = 'bobi@microweber.com';
            $user->save();

            $data = [];
            $data['option_value'] = 'y';
            $data['option_key'] = 'captcha_disabled';
            $data['option_group'] = 'users';
            save_option($data);


            $browser->visit($siteUrl . 'admin/login');
            $browser->pause('2000');

            $browser->click('@forgot-password-link');
            $browser->pause('3000');

            $browser->type('username', 'bobi@microweber.com');
            $browser->click('@reset-password-button');
            $browser->pause('4000');

            $browser->waitForText('We have emailed your password reset link');
            $browser->assertSee('We have emailed your password reset link');

            $sendTime = Carbon::now();

            $findPasswordReset = PasswordReset::where('email', 'bobi@microweber.com')->orderBy('created_at', 'DESC')->first();
            $this->assertNotEmpty($findPasswordReset);
            $this->assertTrue($sendTime > $findPasswordReset->created_at);

            $browser->visit($siteUrl . 'reset-password/'.md5($findPasswordReset->token).'?email=bobi@microweber.com');


            $browser->waitForText('Reset Password');
            $browser->assertSee('Reset Password');


            $browser->pause('14000');


        });
    }

    public function testSubmitWrongEmail()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $data = [];
            $data['option_value'] = 'y';
            $data['option_key'] = 'captcha_disabled';
            $data['option_group'] = 'users';
            save_option($data);

            $browser->visit($siteUrl . 'admin/login');
            $browser->pause('2000');

            $browser->click('@forgot-password-link');
            $browser->pause('3000');

            $browser->type('username', 'wrong-email@microweber.com');
            $browser->click('@reset-password-button');
            $browser->pause('3000');

            $browser->waitForText('We can\'t find a user with that email address');
            $browser->assertSee('We can\'t find a user with that email address');
        });
    }


    public function testCaptchaValidation()
    {

        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $data = [];
            $data['option_value'] = 'n';
            $data['option_key'] = 'captcha_disabled';
            $data['option_group'] = 'users';
            save_option($data);

            $browser->visit($siteUrl . 'admin/login');
            $browser->pause('2000');

            $browser->click('@forgot-password-link');
            $browser->pause('3000');

            $browser->type('username', 'bobi@microweber.com');
            $browser->click('@reset-password-button');
            $browser->pause('4000');

            $browser->waitForText('Invalid captcha answer');
            $browser->assertSee('Invalid captcha answer');

        });
    }
}
