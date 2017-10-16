<?php

namespace SpExt\Pvz\Model;

use SpExt\ApiWrapper\Http\Rules;
use SpExt\ApiWrapper\Model\AbstractModel;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrackNumber extends AbstractModel
{
    const MIN_LENGTH = 9;
    const MAX_LENGTH = 9;

    /**
     * Parcel track number
     *
     * @var string
     */
    public $track_number;

    public static function rules()
    {
        return Rules::create([
            'track_number' => [
                new NotBlank(),
                new Length(['min' => static::MIN_LENGTH, 'max' => static::MAX_LENGTH])
            ]
        ]);
    }
}
