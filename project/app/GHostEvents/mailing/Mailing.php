<?php

namespace App\Mailing;

interface Mailing
{
    public function sendTest();
    public function newMail(): Sending;
    public function newHTMLMail(): Sending;
}
