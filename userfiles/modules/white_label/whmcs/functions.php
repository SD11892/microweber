<?php
include "whmcs.class.php";

api_expose_admin('whitelabel/whmcs_status', function() {

    $settings = get_whitelabel_whmcs_settings();

    if(!isset($settings['whmcs_url'] ) or (isset($settings['whmcs_url']) and !$settings['whmcs_url'])){
        return ['warning'=> 'WHMCS connection is not set'];
    }

    try {
        $whmcs = new WHMCS();
        $whmcs->setUrl($settings['whmcs_url'] . '/includes/api.php');

        if ($settings['whmcs_auth_type'] == 'password') {
            $whmcs->setUsername($settings['whmcs_username']);
            $whmcs->setPassword($settings['whmcs_password']);
        } else {
            $whmcs->setIdentifier($settings['whmcs_api_identifier']);
            $whmcs->setSecret($settings['whmcs_api_secret']);
        }

        $status = $whmcs->getProducts();
    } catch (\Exception $e) {
        return ['error'=> $e->getMessage()];
    }

    if (empty($status)) {
        return ['error'=>'Something went wrong. Can\'t connect to the WHMCS.'];
    }

    if (isset($status['result']) && $status['result'] == 'error') {
        return ['error'=>$status['message']];
    }

    return ['success'=>'Connection with WHMCS is successfully.'];

});

function get_whitelabel_whmcs_settings() {

    $whmcs_url = false;
    $whmcs_auth_type = false;
    $whmcs_api_identifier = false;
    $whmcs_api_secret = false;
    $whmcs_username = false;
    $whmcs_password = false;

    $settings = get_white_label_config();
    if (isset($settings['whmcs_url'])) {
        $whmcs_url = $settings['whmcs_url'];
    }
    if (isset($settings['whmcs_auth_type'])) {
        $whmcs_auth_type = $settings['whmcs_auth_type'];
    }
    if (isset($settings['whmcs_api_identifier'])) {
        $whmcs_api_identifier = $settings['whmcs_api_identifier'];
    }
    if (isset($settings['whmcs_api_secret'])) {
        $whmcs_api_secret = $settings['whmcs_api_secret'];
    }
    if (isset($settings['whmcs_username'])) {
        $whmcs_username = $settings['whmcs_username'];
    }
    if (isset($settings['whmcs_password'])) {
        $whmcs_password = $settings['whmcs_password'];
    }

    return [
          'whmcs_url'=>$whmcs_url,
          'whmcs_auth_type'=>$whmcs_auth_type,
          'whmcs_api_identifier'=>$whmcs_api_identifier,
          'whmcs_api_secret'=>$whmcs_api_secret,
          'whmcs_username'=>$whmcs_username,
          'whmcs_password'=>$whmcs_password
    ];
}
