<?php

namespace MicroweberPackages\User\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Utils\Mail\MailSender;


class UserLoginControllerTest extends TestCase
{
    use UserTestHelperTrait;

    public function testUserLoginWithUsername()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableEmailVerify();

        $username = 'testuser_' . uniqid();
        $password = 'pass__' . uniqid();

        $user = $this->_registerUserWithUsername($username,$password);

        $response = $this->json(
            'POST',
            route('api.user.login'),
            [
                'username' => $username,
                'password' => $password,
            ]
        );

        $userData = $response->getData();
         $this->assertEquals($username, $userData->username);
        $this->assertNotEmpty($userData->id);

        $this->assertTrue(($userData->id > 0));

        $this->assertEquals(200, $response->status());

    }

    public function testUserLoginWithEmail()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();

        $email = 'testusexXr_' . uniqid().'@aa.bb';
        $password = 'pass__' . uniqid();

        $user = $this->_registerUserWithEmail($email,$password);

        $response = $this->json(
            'POST',
            route('api.user.login'),
            [
                'email' => $user->email,
                'password' => $password,
            ]
        );

        $userData = $response->getData();

        $this->assertEquals($email, $userData->email);
        $this->assertNotEmpty($userData->id);

        $this->assertTrue(($userData->id > 0));

        $this->assertEquals(200, $response->status());

    }

    public function testUserLoginWithEmailInUsernameField()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();

        $email = 'testusexXr_' . uniqid().'@aa.bb';
        $password = 'pass__' . uniqid();

        $user = $this->_registerUserWithEmail($email,$password);

        $response = $this->json(
            'POST',
            route('api.user.login'),
            [
                'username' => $user->email,
                'password' => $password,
            ]
        );

        $userData = $response->getData();

        $this->assertEquals($email, $userData->email);
        $this->assertNotEmpty($userData->id);

        $this->assertTrue(($userData->id > 0));

        $this->assertEquals(200, $response->status());

    }

}