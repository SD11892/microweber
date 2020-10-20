<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/20/2020
 * Time: 3:40 PM
 */

namespace MicroweberPackages\User\tests;



trait UserTestHelperTrait {

    private static $_username = false;
    private static $_password = false;
    private static $_email = false;

    private function _disableCaptcha()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'captcha_disabled';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }
    private function _enableCaptcha()
    {
        $data['option_value'] = 'n';
        $data['option_key'] = 'captcha_disabled';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }

    private function _enableUserRegistration()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'enable_user_registration';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _disableUserRegistration()
    {
        $data['option_value'] = 'n';
        $data['option_key'] = 'enable_user_registration';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }

    private function _disableUserRegistrationWithDisposableEmail()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'disable_registration_with_temporary_email';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _disableRegistrationApproval()
    {
        $data['option_value'] = 'n';
        $data['option_key'] = 'registration_approval_required';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _enableRegistrationApproval()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'registration_approval_required';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _enableRegisterEmail()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'register_email_enabled';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

}