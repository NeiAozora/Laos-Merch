<?php

use MailerSend\MailerSend;

class MailHelpers {

    public static function new(): MailerSend
    {
        return new MailerSend(['api_key' => 'mlsn.f3635675b3ec0e07e8703a83cc8eb0e1a87bc9b36fc96ba774208cb6950c7d1e']);
    }
}