<?php

namespace App\Mailing;

use App\Event;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class BasicMailer implements Mailing
{

    /**
     * @var string
     */
    private $appMailAddress;

    /**
     * @var string
     */
    private $appSenderName;

    function __construct()
    {
        $this->appSenderName = 'G-host Team';
    }

    private function prepareMail()
    {
        $mail = new PHPMailer(TRUE);
        if (config('mail.driver') == 'smtp') {
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = config('mail.encryption'); // secure transfer enabled REQUIRED for Gmail
        }
        $mail->Host = config('mail.host');
        $mail->Port = config('mail.port');

        $mail->Username = config('mail.username');
        $mail->Password = config('mail.password');

        $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
        return $mail;
    }

    function sendTest()
    {
        try {
            $mail = $this->prepareMail();
            $mail->addAddress($this->appMailAddress, 'Tester');
            $mail->Subject = 'Mailing test';
            $mail->Body = "Test message";
            $mail->send();
        } catch (MailException $e) {
            echo $e->errorMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    function newMail(): Sending
    {
        return new Email($this->prepareMail());
    }

    function newHTMLMail(): Sending
    {
        $mail = $this->prepareMail();
        $mail->IsHTML(true);
        return new Email($mail);
    }
}