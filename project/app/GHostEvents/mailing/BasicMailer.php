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
        $this->appMailAddress = env('MAIL_TEST_ADDRESS');
        $this->appSenderName = 'G-host Team';
    }

    private function prepareMail()
    {
        $mail = new PHPMailer(TRUE);
        if (env('MAIL_DRIVER') == 'smtp') {
            $mail->IsSMTP(true); // enable SMTP
            $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true; // authentication enabled
            if (env('MAIL_SECURE'))
                $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        }
        $mail->Host = env('MAIL_HOST');
        $mail->Port = env('MAIL_PORT'); // or 587

        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');

        $mail->setFrom($this->appMailAddress, $this->appSenderName);
        return $mail;
    }

    function sendTest()
    {
        try {
            $mail = $this->prepareMail();
            $mail->addAddress($this->appMailAddress, 'Tester');
            $mail->Subject = 'Mailing test';
            //$mail->IsHTML(true);
//            $mail->Body = view('emails.event-invitation-email', [
//                'eventLink' => "localhost",
//                'acceptInvitationLink' => "localhost",
//                'event' => Event::find(1)
//            ])->render();
            $mail->Body = "Test message";
            $mail->send();

        } catch (MailException $e) {
            echo $e->errorMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    function newMail() : Sending {
        return $this->prepareMail();
    }

    function newHTMLMail() : Sending {
        $mail = $this->prepareMail();
        $mail->IsHTML(true);
        return $mail;
    }
}