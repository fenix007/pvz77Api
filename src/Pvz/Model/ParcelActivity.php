<?php

namespace SpExt\Pvz\Model;

use SpExt\ApiWrapper\Http\Rules;
use SpExt\ApiWrapper\Model\AbstractModel;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class ParcelActivity extends AbstractModel
{
    /**
     * Order state
     */
    const STATE_DRAFT = 0;
    const STATE_CREATED = 1;
    const STATE_IN_STORAGE = 2;
    const STATE_READY = 3;
    const STATE_ISSUED = 4; //User received order
    const STATE_PROC_TO_PVZ = 5; //Move to pvz for recipient
    const STATE_PROC_TO_STORAGE = 6; //Move to storage from sender
    const STATE_MOVE_ISSUE = 7;
    const STATE_MOVE_RECIP = 8;

    const STATUS_TEXT = [
        self::STATE_DRAFT => 'Черновик',
        self::STATE_CREATED => 'Сформированно',
        self::STATE_IN_STORAGE => 'Принято на склад',
        self::STATE_READY => 'Готово к выдаче',
        self::STATE_ISSUED => 'Выдано',
        self::STATE_PROC_TO_PVZ => 'Отказ',
        self::STATE_PROC_TO_STORAGE => 'Передано курьеру',
        self::STATE_MOVE_ISSUE => 'Перемещение [выдача]',
        self::STATE_MOVE_RECIP => 'Перемещение [прием]'
    ];

    /** @var  string */
    public $track_number;
    
    /** @var  string */
    public $password;
    
    /** @var  string */
    public $date;
    
    /** @var  string */
    public $state;
    
    /** @var  string */
    public $point;
   
    /** @var  string */
    public $merchant_order_id;
   
    /** @var  string */
    public $merchant_comment;

    public static function rules()
    {
       return Rules::create([
           'track_number' => [
               new NotBlank(),
               new Length(['min' => TrackNumber::MIN_LENGTH, 'max' => TrackNumber::MAX_LENGTH])
           ],
           'point' => [
               new NotBlank(),
               new Type('int'),
               // 0 use for tests
               new Range(['min' => 0, 'max' => Parcel::DELIVERY_COURIER])
           ],
           'state' => [
               new Range(['min' => static::STATE_DRAFT, 'max' => static::STATE_MOVE_RECIP])
           ]
       ]);
    }

    protected function getStateText()
    {
        return static::getTextByState($this->state);
    }

    /**
     * @return array
     */
    public function dateAndText()
    {
        return [
            'date' => $this->date,
            'text' => $this->getStateText()
        ];
    }

    public static function getTextByState($state)
    {
        $statusTexts = static::STATUS_TEXT;

        return isset($statusTexts[$state]) ? $statusTexts[$state] : '';
    }
}
