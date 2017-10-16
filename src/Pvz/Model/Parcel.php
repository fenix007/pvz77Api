<?php

namespace SpExt\Pvz\Model;

use SpExt\ApiWrapper\Http\Rules;
use SpExt\ApiWrapper\Model\AbstractModel;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class Parcel extends AbstractModel
{
    /**
     * Cache on delivery
     */
    const PAYMETHOD_NO = 0;
    const PAYMETHOD_ENVELOPE = 1;
    const PAYMETHOD_CASH = 2;

    const PAYMETHOD_TEXT = [
        self::PAYMETHOD_NO => 'Без оплаты',
        self::PAYMETHOD_ENVELOPE => 'Оплата в конверте',
        self::PAYMETHOD_CASH => 'Оплата наличными'
    ];

    /**
     * Security mode on pvz for get order
     */
    const SECURITY_NO = 0;
    const SECURITY_PASSWORD = 1;
    const SECURITY_PIN = 2;

    const DELIVERY_COURIER = 999;

    /** @var  ParcelRecipient */
    public $recipient;

    /** @var  ParcelSender */
    public $sender;


    /** @var  int */
    public $delivery_point;


    /** @var  int */
    public $is_package = 0;

    /** @var  int */
    public $pay_method = self::PAYMETHOD_NO;

    /** @var  int */
    public $pay_sum = 0;

    /** @var  int */
    public $security_mode = self::SECURITY_NO;

    /** @var  string */
    public $pin = '';

    /** @var  int */
    public $draft = 0;

    /** @var  int */
    public $tracking = 1; // tracking by default


    /** @var  string */
    public $merchant_order_id = '';

    /** @var  string */
    public $merchant_comment = '';


    /** @var  string */
    public $track_number;

    /** @var  int */
    public $edit_allowed;

    /** @var  int */
    public $delete_allowed;

    /** @var  int */
    public $state;

    /** @var  int */
    public $weight = 0;

    /** @var  string */
    public $storage_start = '';

    /** @var  int */
    public $current_point = 0;

    /** @var  array */
    public $activity = [];

    public static function rules()
    {
        return Rules::create([
            'delivery_point' => [
                new NotBlank(),
                new Type('int'),
                // 0 use for tests
                new Range(['min' => 0, 'max' => static::DELIVERY_COURIER])
            ],
            'state' => [
                new Range(['min' => ParcelActivity::STATE_DRAFT, 'max' => ParcelActivity::STATE_MOVE_RECIP])
            ]
        ]);
    }

    public static function create(array $data = [])
    {
        if (!isset($data['recipient'])) {
            $data['recipient'] = ParcelRecipient::create(static::getDataWithPrefix($data, 'recipient_'));
        }

        if (!isset($data['sender'])) {
            $data['sender'] = ParcelSender::create(static::getDataWithPrefix($data, 'sender_'));
        }

        return parent::create($data);
    }

    /**
     * @return Parcel
     */
    public function toDraft()
    {
        $this->draft = 1;

        return $this;
    }

    /**
     * @return Parcel
     */
    public function fromDraft()
    {
        $this->draft = 0;

        return $this;
    }
}
