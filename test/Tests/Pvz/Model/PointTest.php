<?php

namespace Tests\Pvz\Model;

use SpExt\Pvz\Model\Point;
use Tests\Pvz\TestCase;

class PointTest extends TestCase
{
    protected static $PARCEL_POINT = [
        'id'          => 'test_id',
        'title'       => 'test_title',
        'address'     => 'test_address',
        'phone'       => 'test_phone',
        'hours'       => 'test_hours',
        'description' => 'test_description',
        'longitude'   => 33.55,
        'latitude'    => 54.55,
    ];

    /** @var  Point */
    private $point;

    public function setUp()
    {
        parent::setUp();

        $this->point = Point::create(static::$PARCEL_POINT);
    }

    public function tearDown()
    {
        unset($this->point);

        parent::tearDown();
    }

    public function testCreate()
    {
        $this->assertEquals($this->point->title, static::$PARCEL_POINT['title']);
        $this->assertEquals($this->point->address, static::$PARCEL_POINT['address']);
        $this->assertEquals($this->point->phone, static::$PARCEL_POINT['phone']);
        $this->assertEquals($this->point->hours, static::$PARCEL_POINT['hours']);
        $this->assertEquals($this->point->description, static::$PARCEL_POINT['description']);
        $this->assertEquals($this->point->longitude, static::$PARCEL_POINT['longitude']);
        $this->assertEquals($this->point->latitude, static::$PARCEL_POINT['latitude']);
    }

    public function testToArray()
    {
        $this->assertEquals(static::$PARCEL_POINT, $this->point->toArray());
    }
}
