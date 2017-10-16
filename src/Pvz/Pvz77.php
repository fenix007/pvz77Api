<?php

namespace SpExt\Pvz;

use SebastianBergmann\GlobalState\RuntimeException;
use SpExt\ApiWrapper\Model\ScalarMapper;
use SpExt\Pvz\Model\Parcel;
use SpExt\Pvz\Model\ParcelActivity;
use SpExt\Pvz\Model\Point;
use SpExt\Pvz\Model\TrackNumber;

class Pvz77
{
    use PvzClient;

    /**
     * @return Point[]
     */
    public function getPoints($withoutDelivery = false)
    {
        $points = $this->pvz->apiRequest('GetPoints', [], Point::class, 'points');

        if ($withoutDelivery) {
            $points = array_filter($points, function (Point $point) {
                return $point->id != Parcel::DELIVERY_COURIER;
            });
        }

        return $points;
    }

    /**
     * @return Point
     */
    public function getDeliveryPoint()
    {
        return $this->getPoint(Parcel::DELIVERY_COURIER);
    }

    /**
     * @param $pointId
     *
     * @return Point
     */
    public function getPoint($pointId)
    {
        $points = array_filter($this->getPoints(), function (Point $point) use ($pointId) {
            return $point->id == $pointId;
        });

        if (! count($points)) {
            return null;
        }

        return array_pop($points);
    }

    /**
     * @return int
     */
    public function getPackageCost()
    {
        return $this->pvz->apiRequest('GetPackageCost', [], ScalarMapper::class, 'price');
    }

    /**
     * @param Parcel $parcel
     *
     * @return TrackNumber
     * @throws \InvalidArgumentException
     */
    public function createParcel(Parcel $parcel)
    {
        return $this->pvzWithoutCache->apiRequest('CreateParcel', $parcel, TrackNumber::class);
    }

    /**
     * @param array|TrackNumber $trackNumber
     *
     * @return Parcel
     * @throws \InvalidArgumentException
     */
    public function getParcel($trackNumber)
    {
        return $this->pvzWithoutCache->apiRequest('GetParcel', $trackNumber, Parcel::class);
    }

    /**
     * @param Parcel $parcel
     *
     * @return TrackNumber
     * @throws \InvalidArgumentException
     */
    public function editParcel(Parcel $parcel)
    {
        return $this->pvzWithoutCache->apiRequest('EditParcel', $parcel, TrackNumber::class);
    }

    /**
     * @param Parcel $parcel
     *
     * @return TrackNumber
     * @throws \InvalidArgumentException
     */
    public function deleteParcel(Parcel $parcel)
    {
        return $this->pvzWithoutCache->apiRequest('DeleteParcel', $parcel, TrackNumber::class);
    }

    /**
     * Create Parcel Activity
     *
     * @param array $data
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function createActivity(array $data)
    {
        $parcelActivity = ParcelActivity::create($data);
        $parcelActivity->validate();

        if ($parcelActivity->password !== md5($this->config->password)) {
            throw new \InvalidArgumentException('Invalid password');
        }

        return $parcelActivity;
    }
}
