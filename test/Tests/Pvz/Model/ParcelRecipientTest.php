<?php

namespace Tests\Pvz\Model;

use SpExt\Pvz\Model\ParcelRecipient;
use Tests\Pvz\TestCase;

class ParcelRecipientTest extends TestCase
{
    use ParcelRecipientTrait;

    /** @var  ParcelRecipient */
    private $parcelRecipient;

    public function setUp()
    {
        parent::setUp();

        $this->parcelRecipient = static::createParcelRecipient();
    }

    public function tearDown()
    {
        unset($this->parcelRecipient);

        parent::tearDown();
    }

    public function testCreate()
    {
        $this->assertEquals($this->parcelRecipient->nick, static::$PARCEL_RECIPIENT['nick']);
        $this->assertEquals($this->parcelRecipient->name, static::$PARCEL_RECIPIENT['name']);
        $this->assertEquals($this->parcelRecipient->phone, static::$PARCEL_RECIPIENT['phone']);
        $this->assertEquals($this->parcelRecipient->email, static::$PARCEL_RECIPIENT['email']);
        $this->assertEquals($this->parcelRecipient->address, static::$PARCEL_RECIPIENT['address']);
    }

    public function testToArray()
    {
        $result = array_combine(
            array_map(function ($field) {
                return static::$PARCEL_RECIPIENT_PREFIX . $field;
            },
            array_keys(static::$PARCEL_RECIPIENT)),
            static::$PARCEL_RECIPIENT
        );

        $this->assertEquals($result, $this->parcelRecipient->toArray());
    }

    public function testValidate_success()
    {
        $errors = $this->parcelRecipient->validate();

        $this->assertCount(0, $errors);
    }

    public function testValidate_failed()
    {
        $parcelRecipientNotValid = static::createParcelRecipientInvalid();

        $errors = $parcelRecipientNotValid->validate();

        $this->assertCount(count(static::$PARCEL_RECIPIENT_INVALID), $errors);
    }
}
