<?php

namespace Microweber\tests;

<<<<<<< HEAD
=======

>>>>>>> eaa6b6282944b17c4a2c870d2305b8781f3e0182
use MicroweberPackages\Utils\Mail\MailSender;

/**
 * Run test
 * @author Bobi Slaveykvo Microweber
 * @command php phpunit.phar --filter MailSenderTest
 */
class MailSenderTest extends TestCase
{
    public function testSend()
    {
        $to = 'bobi@microweber.com';
        $subject = 'Email subject';
        $replyTo = 'Reply to';
        $content = 'This is example message.';
        $from = 'peter@microweber.com';
        $fromName = 'Peter Microweber';

        $mail = new MailSender();
        $mail->setEmailTo($to);
        $mail->setEmailSubject($subject);
        $mail->setEmailReplyTo($replyTo);
        $mail->setEmailMessage($content);
        $mail->setEmailFrom($from);
        $mail->setEmailFromName($fromName);
        $mail->send();

        $checkEmailContent = MailSender::$last_send;

        $this->assertSame($checkEmailContent['content'], $content);
        $this->assertSame($checkEmailContent['from_name'], $fromName);
        $this->assertSame($checkEmailContent['from'], $from);
        $this->assertSame($checkEmailContent['to'], $to);
        $this->assertSame($checkEmailContent['subject'], $subject);
    }

}
