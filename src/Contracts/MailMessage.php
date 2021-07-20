<?php


namespace BinomeWay\NovaContactTool\Contracts;

use BinomeWay\NovaContactTool\Models\Subscriber;

interface MailMessage
{
    public function getSubject() : string;

    public function getSubscriber() : Subscriber;

    public function getMeta() : array;

    public function getMessage() : string;
}
