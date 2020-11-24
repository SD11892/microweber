<?php

namespace MicroweberPackages\Notification\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\MailChannel;

class AppMailChannel extends MailChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {

         if (!\Config::get('mail.transport')) {
            return;
        }

        try {
            return parent::send($notifiable, $notification);
        } catch (\Swift_RfcComplianceException $e) {
            \Log::error($e);
        } catch (\Swift_TransportException $e) {
            \Log::error($e);
        }

//        catch (\Exception $e) {
//            \Log::error($e);
//        }
    }
}