<?php

use MailerSend\MailerSend;

class MailHelpers {

    public static function new(): MailerSend
    {
        return new MailerSend(['api_key' => 'mlsn.78675a5cab1a16e755bfbbf88be78336610b540eca8cb07a290850ced811db86']);
    }
}