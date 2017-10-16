<?php

namespace Tests\Pvz\Model;

use SpExt\Pvz\Model\ParcelSender;

trait ParcelSenderTrait
{
    protected static $PARCEL_SENDER        = [
        'nick'  => 'test_nick',
        'name'  => 'test_name',
        'phone' => 'test_phone'
    ];

    protected static  $PARCEL_SENDER_INVALID        = [
        'nick'  => '',
        'name'  => 'te',
        'phone' => ''
    ];

    protected static $PARCEL_SENDER_PREFIX = 'sender_';

    protected static function createParcelSender()
    {
        return ParcelSender::create(static::$PARCEL_SENDER);
    }

    protected static function createParcelSenderInvalid()
    {
        return ParcelSender::create(static::$PARCEL_SENDER_INVALID);
    }
}
