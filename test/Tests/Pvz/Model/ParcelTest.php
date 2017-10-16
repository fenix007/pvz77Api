<?php

namespace Tests\Pvz\Model;

use SpExt\Pvz\Model\Parcel;
use SpExt\Pvz\Model\ParcelRecipient;
use SpExt\Pvz\Model\ParcelSender;
use Tests\Pvz\TestCase;

class ParcelTest extends TestCase
{
    use ParcelSenderTrait;
    use ParcelRecipientTrait;
    use ParcelTrait;

    /** @var  Parcel */
    protected $parcel;

    /** @var  ParcelRecipient */
    protected $parcelRecipient;

    /** @var  ParcelSender */
    protected $parcelSender;

    public function setUp()
    {
        parent::setUp();

        $this->parcelRecipient = static::createParcelRecipient();
        $this->parcelSender= static::createParcelSender();
        $this->parcel = static::createParcel($this->parcelRecipient, $this->parcelSender);
    }

    public function tearDown()
    {
        unset($this->parcel);
        unset($this->parcelRecipient);
        unset($this->parcelSender);

        parent::tearDown();
    }

    public function testCreate()
    {
        $this->assertEquals($this->parcelRecipient, $this->parcel->recipient);
        $this->assertEquals($this->parcelSender, $this->parcel->sender);
        $this->assertEquals(static::$PARCEL['delivery_point'], $this->parcel->delivery_point);
    }

    public function testToArray()
    {
        $parcelArray = array_merge(
            $this->parcelRecipient->toArray(), //with prefix
            $this->parcelSender->toArray(), //with prefix
            static::$PARCEL
        );

        $this->assertIsSubArray($parcelArray, $this->parcel->toArray());
    }

    public function testValidate_success()
    {
        $errors = $this->parcel->validate();

        $this->assertCount(0, $errors);
    }

    public function testValidate_failed()
    {
        $parcelInvalid = $this->createParcelInvalid(
            $this->createParcelRecipientInvalid(),
            $this->createParcelSenderInvalid()
        );

        $errors = $parcelInvalid->validate();

        $countErrors = count(static::$PARCEL_INVALID) +
            count(static::$PARCEL_RECIPIENT_INVALID) +
            count(static::$PARCEL_SENDER_INVALID);

        $this->assertCount($countErrors, $errors);
    }
}
