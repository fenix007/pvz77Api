<?php

namespace Tests\Pvz\Model;

use Tests\Pvz\TestCase;

class ParcelSenderTest extends TestCase
{
    use ParcelSenderTrait;

    /** @var  \SpExt\Pvz\Model\ParcelSender */
    private $parcelSender;

    public function setUp()
    {
        parent::setUp();

        $this->parcelSender = static::createParcelSender();
    }

    public function tearDown()
    {
        unset($this->parcelSender);

        parent::tearDown();
    }

    public function testCreate()
    {
        $this->assertEquals($this->parcelSender->nick, static::$PARCEL_SENDER['nick']);
        $this->assertEquals($this->parcelSender->name, static::$PARCEL_SENDER['name']);
        $this->assertEquals($this->parcelSender->phone, static::$PARCEL_SENDER['phone']);
    }

    public function testToArray()
    {
        $result = array_combine(
            array_map(function ($field) {
                return static::$PARCEL_SENDER_PREFIX . $field;
            },
                array_keys(static::$PARCEL_SENDER)),
            static::$PARCEL_SENDER
        );

        $this->assertEquals($result, $this->parcelSender->toArray());
    }

    public function testValidate_success()
    {
        $errors = $this->parcelSender->validate();

        $this->assertCount(0, $errors);
    }

    public function testValidate_failed()
    {
        $notValidParcelSender = static::createParcelSenderInvalid();

        $errors = $notValidParcelSender->validate();

        $this->assertCount(count(static::$PARCEL_SENDER_INVALID), $errors);
    }
}
