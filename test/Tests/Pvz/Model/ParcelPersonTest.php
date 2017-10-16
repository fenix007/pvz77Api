<?php

namespace Tests\Pvz\Model;

use SpExt\Pvz\Model\ParcelPerson;
use Tests\Pvz\TestCase;

class ParcelPersonTest extends TestCase
{
    protected static $PARCEL_PERSON = [
        'nick' => 'test_nick',
        'name' => 'test_name',
        'phone' => 'test_phone'
    ];

    /** @var  \SpExt\Pvz\Model\ParcelPerson */
    private $parcelPerson;

    public function setUp()
    {
        parent::setUp();

        $this->parcelPerson = ParcelPerson::create(static::$PARCEL_PERSON);
    }

    public function tearDown()
    {
        unset($this->parcelPerson);

        parent::tearDown();
    }

    public function testCreate()
    {
        $this->assertEquals($this->parcelPerson->nick, static::$PARCEL_PERSON['nick']);
        $this->assertEquals($this->parcelPerson->name, static::$PARCEL_PERSON['name']);
        $this->assertEquals($this->parcelPerson->phone, static::$PARCEL_PERSON['phone']);
    }

    public function testToArray()
    {
        $this->assertEquals(static::$PARCEL_PERSON, $this->parcelPerson->toArray());
    }
}
