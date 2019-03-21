<?php
/**
 * Created by PhpStorm.
 * User: espe
 * Date: 21.03.19
 * Time: 18:46
 */

namespace App\Mailing;

use PHPMailer\PHPMailer\PHPMailer;

class Email implements Sending
{
    private $address = "";

    private $receiver = "";

    private $engine;

    function __construct(PHPMailer $engine)
    {
        $this->engine = $engine;
    }

    public function to(string $address): Sending
    {
        $this->address = $address;
        return $this;
    }

    public function as (string $name): Sending
    {
        $this->receiver = $name;
        return $this;
    }

    public function content(string $content): Sending
    {
        $this->engine->Body = $content;
        return $this;
    }

    public function subject(string $subject): Sending
    {
        $this->engine->Subject = $subject;
        return $this;
    }

    public function send()
    {
        $this->engine->addAddress($this->address, $this->receiver);
        $this->engine->send();
    }
}