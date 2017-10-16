<?php

namespace Tests\Pvz\Model;

use SpExt\Pvz\Model\ParcelRecipient;

trait ParcelRecipientTrait
{
    protected static $PARCEL_RECIPIENT = [
        'nick'    => 'test_nick',
        'name'    => 'test_name',
        'phone'   => 'test_phone',
        'email'   => 'test_email@test.tt',
        'address' => 'test_address'
    ];

    protected static $PARCEL_RECIPIENT_INVALID = [
        'nick'  => '',
        'name'  => '12',
        'phone' => '',
        'email' => 'invalid_email',
    ];

    protected static $PARCEL_RECIPIENT_PREFIX = 'recipient_';

    protected static function createParcelRecipient()
    {
        return ParcelRecipient::create(static::$PARCEL_RECIPIENT);
    }

    protected static function createParcelRecipientInvalid()
    {
        return ParcelRecipient::create(static::$PARCEL_RECIPIENT_INVALID);
    }
}
