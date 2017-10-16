<?php

namespace Tests\Pvz;

use SpExt\Pvz\Model\Parcel;
use SpExt\Pvz\Model\Point;
use SpExt\Pvz\Model\TrackNumber;
use SpExt\Pvz\Pvz77;
use SpExt\Pvz\Pvz77Client;
use Tests\Pvz\Model\ParcelActivity;
use Tests\Pvz\Model\ParcelRecipientTrait;
use Tests\Pvz\Model\ParcelSenderTrait;
use Tests\Pvz\Model\ParcelTrait;

class Pvz77Test extends TestCase
{
    use ParcelRecipientTrait;
    use ParcelSenderTrait;
    use ParcelTrait;
    use ParcelActivity;

    const MIN_POINTS = 14;
    const TEST_POINT_ID = 10;

    /** @var  Pvz77Client */
    protected $pvz;

    /** @var  Parcel */
    protected $parcel;

    public function setUp()
    {
        parent::setUp();

        $this->parcel = $this->createParcel(
            $this->createParcelRecipient(),
            $this->createParcelSender()
        );

        $this->pvz = new Pvz77();
    }

    public function tearDown()
    {
        unset($this->pvz);
        unset($this->pvz);

        parent::tearDown();
    }

    public function testGetPoints()
    {
        $points = $this->pvz->getPoints();

        $this->assertTrue(count($points) >= static::MIN_POINTS);
    }

    public function testGetPoints_WithoutDelivery()
    {
        $points = $this->pvz->getPoints(true);

        $this->assertTrue(count($points) < static::MIN_POINTS);
    }

    public function testGetPoint()
    {
        $point = $this->pvz->getPoint(static::TEST_POINT_ID);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertNotEmpty($point->title);
    }

    public function testCreateParcel()
    {
        $trackNumber = $this->pvz->createParcel($this->parcel);

        $this->assertInstanceOf(TrackNumber::class, $trackNumber);
    }

    public function testGetParcel()
    {
        $trackNumber = $this->pvz->createParcel($this->parcel);

        $parcelFromPvz = $this->pvz->getParcel($trackNumber);

        $this->assertInstanceOf(Parcel::class, $parcelFromPvz);
        $this->assertEquals($trackNumber->track_number, $parcelFromPvz->track_number);
        $this->assertEquals(\SpExt\Pvz\Model\ParcelActivity::STATE_DRAFT, $parcelFromPvz->state);
        $this->assertEquals(1, $parcelFromPvz->edit_allowed);
        $this->assertEquals(1, $parcelFromPvz->delete_allowed);
        $this->assertArrayExcept($this->parcel->toArray(null, false), $parcelFromPvz->toArray(), static::$NOT_CHECKED_PARCEL_FIELDS);
    }

    public function testEditParcel()
    {
        $trackNumber = $this->pvz->createParcel($this->parcel);

        $parcel = clone $this->parcel;
        $parcel->track_number = $trackNumber->track_number;
        $parcel->merchant_comment = 'test merchant comment';
        $parcel->merchant_order_id = 'test oreder id';

        $trackNumberEdit = $this->pvz->editParcel($parcel);

        $this->assertEquals($trackNumber, $trackNumberEdit);

        $parcelFromPvz = $this->pvz->getParcel($trackNumber);

        $this->assertArrayExcept($parcel->toArray(null, false), $parcelFromPvz->toArray(), static::$NOT_CHECKED_PARCEL_FIELDS);
    }

    public function testDeleteParcel_draft()
    {
        $parcel = clone $this->parcel;
        $trackNumberEdit = $this->pvz->createParcel($parcel->toDraft());
        $parcel->track_number = $trackNumberEdit->track_number;

        $trackNumber = $this->pvz->deleteParcel($parcel);

        $this->assertEquals($trackNumberEdit, $trackNumber);
    }

    public function testDeleteParcel_noDraft()
    {
        $this->expectException(\RuntimeException::class);

        $parcel = clone $this->parcel;
        $trackNumberEdit = $this->pvz->createParcel($parcel->fromDraft());
        $parcel->track_number = $trackNumberEdit->track_number;

        $this->pvz->deleteParcel($parcel);
    }

    public function testCreateParcelActivity_invalidPassword()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->pvz->createActivity(static::$ACTIVITY_DATA);
    }

    public function testCreateParcelActivity()
    {
        $activityData = static::$ACTIVITY_DATA;
        $activityData['password'] = $this->createConfigWithCache()->password;

        $parcelActivity = $this->pvz->createActivity($activityData);

        $this->assertInstanceOf(\SpExt\Pvz\Model\ParcelActivity::class, $parcelActivity);
    }
}
