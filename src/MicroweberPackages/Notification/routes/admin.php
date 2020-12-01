<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/10/2020
 * Time: 4:48 PM
 */



Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Notification\Http\Controllers\Admin')
    ->group(function () {

        Route::post('notification/read', 'NotificationController@read')->name('notification.read');
        Route::post('notification/reset', 'NotificationController@reset')->name('notification.reset');

        Route::post('notification/delete', 'NotificationController@delete')->name('notification.delete');
        Route::post('notification/mark_all_as_read', 'NotificationController@markAllAsRead')->name('notification.mark_all_as_read');


        Route::get('notification', 'NotificationController@index')->name('notification.index');
    });