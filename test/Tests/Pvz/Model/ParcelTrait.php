<?php

namespace Tests\Pvz\Model;

use SpExt\Pvz\Model\Parcel;
use SpExt\Pvz\Model\ParcelRecipient;
use SpExt\Pvz\Model\ParcelSender;

trait ParcelTrait
{
    protected static $PARCEL         = [
        'delivery_point' => 999,
        'draft'          => 1
    ];

    protected static $PARCEL_INVALID = [
        'delivery_point' => 9990,
        'state'          => 1000
    ];

    protected static $NOT_CHECKED_PARCEL_FIELDS = [
        'recipient',
        'sender',
        'track_number',
        'state',
        'edit_allowed',
        'delete_allowed',
    ];

    /**
     * @param ParcelRecipient $parcelRecipient
     * @param ParcelSender    $parcelSender
     *
     * @return Parcel
     */
    protected static function createParcel(ParcelRecipient $parcelRecipient, ParcelSender $parcelSender)
    {
        $parcelData = array_merge(
            ['recipient' => $parcelRecipient],
            ['sender' => $parcelSender],
            static::$PARCEL
        );

        return Parcel::create($parcelData);
    }

    protected static function createParcelInvalid(ParcelRecipient $parcelRecipient, ParcelSender $parcelSender)
    {
        $parcelData = array_merge(
            ['recipient' => $parcelRecipient],
            ['sender' => $parcelSender],
            static::$PARCEL_INVALID
        );

        return Parcel::create($parcelData);
    }
}
