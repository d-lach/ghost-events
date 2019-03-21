<?php

namespace App\Mailing;

interface Sending
{
    public function to(string $address): Sending;
    public function as(string $name): Sending;
    public function subject(string $subject): Sending;
    public function content(string $htmlContent): Sending;
    public function send();
}
